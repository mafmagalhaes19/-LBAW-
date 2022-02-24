
DROP SCHEMA IF EXISTS lbaw2115 CASCADE;
CREATE SCHEMA lbaw2115;
SET search_path TO lbaw2115;

-----------------------------------------
-- Types
-----------------------------------------
DROP TYPE  IF EXISTS eventstate CASCADE;
CREATE TYPE eventstate AS ENUM ('Scheduled','Ongoing','Canceled','Finished');

-----------------------------------------
-- Tables
-----------------------------------------

DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users
(
    id SERIAL PRIMARY KEY,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    description TEXT DEFAULT NULL,
    profilepictureurl TEXT,
    isadmin BOOLEAN NOT NULL DEFAULT FALSE,
    registrationdate DATE NOT NULL DEFAULT CURRENT_DATE
);


DROP TABLE IF EXISTS event_tag CASCADE;
CREATE TABLE event_tag
(
    id SERIAL PRIMARY KEY,
    tagname TEXT NOT NULL UNIQUE
);


DROP TABLE IF EXISTS eventg CASCADE;
CREATE TABLE eventg
(
    id SERIAL PRIMARY KEY,
    event_description TEXT NOT NULL,
    eventname TEXT NOT NULL,
    startdate DATE NOT NULL,
    enddate DATE NOT NULL,
    place TEXT NOT NULL,
    duration INTERVAL NOT NULL,
    eventstate eventstate NOT NULL,
    isprivate BOOLEAN NOT NULL DEFAULT FALSE,
    pictureurl TEXT,
    tagid INTEGER NOT NULL REFERENCES event_tag(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT dates CHECK (startdate <= enddate)
);


DROP TABLE IF EXISTS event_role CASCADE;
CREATE TABLE event_role
(
        id SERIAL PRIMARY KEY,
        userid INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
        eventid INTEGER NOT NULL REFERENCES eventg(id) ON DELETE CASCADE ON UPDATE CASCADE,
        ishost BOOLEAN NOT NULL,
         UNIQUE (userid, eventid, ishost)
);

DROP TABLE IF EXISTS invite CASCADE;
CREATE TABLE invite
(
    id SERIAL PRIMARY KEY,
    receiverid INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    senderid INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    eventid INTEGER NOT NULL REFERENCES eventg(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CHECK (receiverid <> senderid),
    UNIQUE (receiverid,eventid));



DROP TABLE IF EXISTS ask_access CASCADE;
CREATE TABLE ask_access
(
    id SERIAL PRIMARY KEY,
    participant INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    eventid INTEGER NOT NULL REFERENCES eventg(id) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE IF EXISTS event_announcement CASCADE;
CREATE TABLE event_announcement
(
    id SERIAL PRIMARY KEY,
    messagea TEXT NOT NULL,
    role_id INTEGER NOT NULL REFERENCES event_role (id) ON DELETE CASCADE ON UPDATE CASCADE

);

DROP TABLE IF EXISTS event_comment CASCADE;
CREATE TABLE event_comment
(
    id SERIAL PRIMARY KEY,
    messagec TEXT NOT NULL,
    role_id INTEGER NOT NULL REFERENCES event_role (id) ON DELETE CASCADE ON UPDATE CASCADE,
    photo BYTEA DEFAULT NULL
);

DROP TABLE IF EXISTS event_poll CASCADE;
CREATE TABLE event_poll
(
    id SERIAL PRIMARY KEY,
    messagep TEXT NOT NULL,
    role_id INTEGER NOT NULL REFERENCES event_role (id) ON DELETE CASCADE ON UPDATE CASCADE
);


DROP TABLE IF EXISTS poll_option CASCADE;
CREATE TABLE poll_option
(
    id SERIAL PRIMARY KEY,
    messagepo TEXT NOT NULL,
    countvote INTEGER DEFAULT 0,
    pollid INTEGER NOT NULL REFERENCES event_poll (id) ON DELETE CASCADE ON UPDATE CASCADE

);


DROP TABLE IF EXISTS vote CASCADE;
CREATE TABLE vote
(
    id SERIAL PRIMARY KEY,
    votetype BOOLEAN NOT NULL,
    event_roleid INTEGER NOT NULL REFERENCES event_role(id) ON DELETE CASCADE ON UPDATE CASCADE,
    commentid INTEGER REFERENCES event_comment (id) ON DELETE CASCADE ON UPDATE CASCADE,
    announcementid INTEGER REFERENCES event_announcement (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CHECK ((announcementid IS NOT NULL AND commentid IS NULL) OR (announcementid IS NULL AND commentid IS NOT NULL))
);


DROP TABLE IF EXISTS reports CASCADE;
CREATE TABLE reports
(
    id SERIAL PRIMARY KEY,
    descriptions TEXT NOT NULL,
    userid INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    eventid INTEGER NOT NULL REFERENCES eventg (id) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS userNotification CASCADE;
CREATE TABLE userNotification
(
    id SERIAL PRIMARY KEY,
    userid INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    eventid INTEGER NOT NULL REFERENCES eventg (id) ON DELETE CASCADE ON UPDATE CASCADE,
    commentid INTEGER REFERENCES event_comment (id) ON DELETE CASCADE ON UPDATE CASCADE,
    announcementid INTEGER REFERENCES event_announcement (id) ON DELETE CASCADE ON UPDATE CASCADE,
    inviteid INTEGER REFERENCES invite (id) ON DELETE CASCADE ON UPDATE CASCADE
);


-----------------------------------------
-- Indexes
-----------------------------------------
DROP INDEX IF EXISTS event_state CASCADE;
CREATE INDEX event_state ON eventg USING hash (eventstate);

DROP INDEX IF EXISTS end_event CASCADE;
CREATE INDEX end_event ON eventg USING btree (enddate);

DROP INDEX IF EXISTS start_event CASCADE;
CREATE INDEX start_event ON eventg USING btree (startdate);


-----------------------------------------
-- FTS Indexes
-----------------------------------------

-- Add column to event to store computed ts_vectors.
ALTER TABLE eventg
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
DROP FUNCTION IF EXISTS event_search_update() CASCADE;
CREATE FUNCTION event_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.eventname), 'A')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.eventname <> OLD.eventname) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english', NEW.eventname), 'A')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on event.
DROP TRIGGER IF EXISTS event_search_update ON eventg CASCADE;
CREATE TRIGGER event_search_update
 BEFORE INSERT OR UPDATE ON eventg
 FOR EACH ROW
 EXECUTE PROCEDURE event_search_update();


-- Finally, create a GIN index for ts_vectors.
DROP INDEX IF EXISTS search_idx CASCADE;
CREATE INDEX search_idx ON eventg USING GIN (tsvectors);

-----------------------------------------
-- Triggers
-----------------------------------------

DROP FUNCTION IF EXISTS comment_in_event_poll() CASCADE;
CREATE FUNCTION comment_in_event_poll() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NOT EXISTS (SELECT * FROM eventg INNER JOIN event_role ON eventg.id = event_role.eventid INNER JOIN users ON event_role.userid = users.id WHERE NEW.id = event_role.eventid ) THEN
           RAISE EXCEPTION 'A user can only comment in an event poll, if he is enrolled in that specific event.';
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS comment_in_event_poll ON event_comment CASCADE;
CREATE TRIGGER comment_in_event_poll
        BEFORE INSERT OR UPDATE ON event_comment
        FOR EACH ROW
        EXECUTE PROCEDURE comment_in_event_poll();


DROP FUNCTION IF EXISTS delete_comment_in_event_poll() CASCADE;
CREATE FUNCTION delete_comment_in_event_poll() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NOT EXISTS (SELECT * FROM eventg INNER JOIN event_role ON eventg.id = event_role.eventid INNER JOIN users ON 
        event_role.userid = users.id WHERE OLD.id = event_role.eventid) THEN
           RAISE EXCEPTION 'A user can only delete a comment in an event poll, if he is enrolled in that specific event and the 
           comment belongs to him.';
        END IF;
        RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_comment_in_event_poll ON event_comment CASCADE;
CREATE TRIGGER delete_comment_in_event_poll
        BEFORE DELETE ON event_comment
        FOR EACH ROW
        EXECUTE PROCEDURE delete_comment_in_event_poll();


--DROP FUNCTION IF EXISTS vote_in_event_poll() CASCADE;
--CREATE FUNCTION vote_in_event_poll() RETURNS TRIGGER AS
--$BODY$
--BEGIN
--        IF EXISTS (SELECT * FROM event_poll INNER JOIN event_role ON role_id = event_role.eventid INNER JOIN users ON event_role.userid = users.id WHERE NEW.role_id = event_role.eventid AND NEW.users.id = event_role.userid) THEN
--           RAISE EXCEPTION 'A users can only vote in an event poll, if he is enrolled in that specific event.';
--        END IF;
--        RETURN NEW;
--END
--$BODY$
--LANGUAGE plpgsql;

--DROP TRIGGER IF EXISTS vote_in_event_poll ON event_poll CASCADE;
--CREATE TRIGGER vote_in_event_poll
--        BEFORE INSERT OR UPDATE ON event_poll
--        FOR EACH ROW
--        EXECUTE PROCEDURE vote_in_event_poll();


DROP FUNCTION IF EXISTS delete_vote_in_event_poll() CASCADE;
CREATE FUNCTION delete_vote_in_event_poll() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NOT EXISTS (SELECT * FROM eventg INNER JOIN event_role ON eventg.id = event_role.eventid INNER JOIN users ON event_role.userid = users.id WHERE OLD.id = event_role.eventid) THEN
           RAISE EXCEPTION 'A users can only delete a vote in an event poll, if he is enrolled in that specific event and the comment belongs to him.';
        END IF;
        RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_vote_in_event_poll ON vote CASCADE;
CREATE TRIGGER delete_vote_in_event_poll
        BEFORE DELETE ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE delete_vote_in_event_poll();

/*
DROP FUNCTION IF EXISTS search_event() CASCADE;
CREATE FUNCTION search_event() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF EXISTS (SELECT * FROM eventg WHERE NEW.id = id AND NEW.isprivate = TRUE) THEN
           RAISE EXCEPTION ' Private events are not shown in search results.';
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS search_event ON event CASCADE;
CREATE TRIGGER search_event
        BEFORE INSERT OR UPDATE ON eventg
        FOR EACH ROW
        EXECUTE PROCEDURE search_event();

*/
DROP FUNCTION IF EXISTS private_event_invite_only() CASCADE;
CREATE FUNCTION private_event_invite_only() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF EXISTS (SELECT * FROM ask_access WHERE NEW.eventid = eventid) THEN
           RAISE EXCEPTION ' Private events are invite only.';
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS private_event_invite_only ON ask_access CASCADE;
CREATE TRIGGER private_event_invite_only
        BEFORE INSERT OR UPDATE ON ask_access
        FOR EACH ROW
        EXECUTE PROCEDURE private_event_invite_only();

/*
DROP FUNCTION IF EXISTS event_schedule() CASCADE;
CREATE FUNCTION event_schedule() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF EXISTS (SELECT * FROM eventg WHERE NEW.id = id AND NEW.enddate > NEW.startdate AND NEW.startdate > CURRENT_DATE ) THEN
           RAISE EXCEPTION ' Ending event date needs to be after starting date and starting date also needs to be at least 1 day after event creation.';
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS event_schedule ON eventg CASCADE;
CREATE TRIGGER event_schedule
        BEFORE INSERT OR UPDATE ON eventg
        FOR EACH ROW
        EXECUTE PROCEDURE event_schedule();

*/
DROP FUNCTION IF EXISTS edit_vote() CASCADE;
CREATE FUNCTION edit_vote() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NOT EXISTS (SELECT * FROM eventg INNER JOIN event_role ON eventg.id = event_role.eventid INNER JOIN users ON event_role.userid = users.id WHERE NEW.id = event_role.eventid) THEN
           RAISE EXCEPTION ' Only participating users can edit and vote on their own comments on the discussion of events.';
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS edit_vote ON vote CASCADE;
CREATE TRIGGER edit_vote
        BEFORE UPDATE ON vote
        FOR EACH ROW
        EXECUTE PROCEDURE edit_vote();


DROP FUNCTION IF EXISTS delete_account() CASCADE;
CREATE FUNCTION delete_account() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NOT EXISTS (SELECT * FROM users WHERE OLD.id = users.id ) THEN
           RAISE EXCEPTION ' Only users can delete their account.';
        END IF;
        RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_acount ON users CASCADE;
CREATE TRIGGER delete_account
        BEFORE DELETE ON users
        FOR EACH ROW
        EXECUTE PROCEDURE delete_account();


DROP FUNCTION IF EXISTS calc_duration() CASCADE;
CREATE FUNCTION calc_duration() RETURNS TRIGGER AS 
$$
BEGIN
        NEW.duration = AGE(NEW.enddate+1,NEW.startdate);
        RETURN NEW;
END
$$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS calc_duration ON eventg CASCADE;
CREATE TRIGGER calc_duration
        BEFORE INSERT ON eventg
        FOR EACH ROW
        EXECUTE PROCEDURE calc_duration();

/*
DROP FUNCTION IF EXISTS delete_account_effects() CASCADE;
CREATE FUNCTION delete_account_effects() RETURNS TRIGGER AS
$BODY$
BEGIN
        DELETE FROM eventg WHERE id IN (SELECT id FROM eventg INNER JOIN event_role eventid ON eventg.id = event_role.eventid 
        INNER JOIN users id ON users.id = event_role.userid WHERE OLD.id = event_role.eventid);

END
$BODY$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_account_effects ON users CASCADE;
CREATE TRIGGER delete_account_effects
        AFTER DELETE ON users
        FOR EACH ROW
        EXECUTE PROCEDURE delete_account_effects();
*/ 


-----------------------------------------
-- Transactions
-----------------------------------------

--BEGIN TRANSACTION
    --SELECT COUNT (*) AS users_found FROM users WHERE username= $username
    --IF users_found = 0
    --BEGIN
        --RETURN ERROR_NOT_FOUND
    --END
    --DELETE FROM users WHERE username = $username
--COMMIT TRANSACTION


-----------------------------------------
-- Population
-----------------------------------------

insert into users (username, email, password, description, profilepictureurl,isadmin) values ('admin', 'admin@theone.com', '$2y$10$75Sdlr3i9/18niLK1pMF0.wq5q9W.U/r4VtvWgaQLOSgDECJDwSqG', NULL, 'https://tinyurl.com/adminlbaw',true);
insert into users (username, email, password, description, profilepictureurl,isadmin) values ('dfrowde1', 'nboulde1@netvibes.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/adminlbaw',true);
insert into users (username, email, password, description, profilepictureurl,isadmin) values ('chutson2', 'caddey2@illinois.edu', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/adminlbaw',true);
insert into users (username, email, password, description, profilepictureurl,isadmin) values ('bdarlasson3', 'mdawton3@google.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/adminlbaw',true);
insert into users (username, email, password, description, profilepictureurl,isadmin) values ('bdawson4', 'bredbourn4@baidu.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/adminlbaw',true);
insert into users (username, email, password, description, profilepictureurl) values ('bleitche5', 'hbowler5@mlb.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('lcreaser6', 'kfowells6@usnews.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('mdacres7', 'tmcgown7@miibeian.gov.cn', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('faslott8', 'lgallally8@desdev.cn', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('saery9', 'kgrovier9@printfriendly.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('kdonovina', 'wtavenera@rambler.ru', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('rsimenetb', 'jmarshamb@mashable.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('ssumnallc', 'clomasc@bbb.org', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('eboundeyd', 'kkieltyd@dell.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('rkimburyf', 'abazeleyf@posterous.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('sortigag', 'phachetteg@chicagotribune.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('hbuscombeh', 'kpatriah@answers.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('tbaishi', 'dcammackei@webs.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('rblanshardj', 'iferencj@nytimes.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('lplatfootk', 'idumberellk@mit.edu', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('sceschinil', 'lserchwelll@google.co.jp', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('mmottleym', 'pcasbournem@loc.gov', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('cknyvettn', 'ceveredn@nytimes.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('nsvaninio', 'ssmedleyo@umich.edu', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('ckaganq', 'mandreuttiq@nifty.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('rmanachr', 'bschlagtmansr@cocolog-nifty.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('pmassinghams', 'bcuvleys@miibeian.gov.cn', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('vsheart', 'jgheorghet@bbc.co.uk', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('jpolinu', 'jrollandu@irs.gov', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('sclackersrr', 'ufontenotrr@samsung.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', NULL, 'https://tinyurl.com/lbawprofilepic');
insert into users (username, email, password, description, profilepictureurl) values ('testing', 'testing@testing.com', '$2a$06$I1SoT.xFQGG3IpSNrOWUXuTjl6Mb.1J6p4awu8b0YG8to4VL4bZEG', 'This is my about page :)', 'https://tinyurl.com/lbawprofilepic');


insert into event_tag (tagname) values('Music');               -- 1       
insert into event_tag (tagname) values('Sports');              -- 2      
insert into event_tag (tagname) values('Movies and TV Shows');         -- 3            
insert into event_tag (tagname) values('Arts and Leisure');    -- 4                 
insert into event_tag (tagname) values('Programming');         -- 5            
insert into event_tag (tagname) values('Lifestyle');         -- 6            
insert into event_tag (tagname) values('Gaming');             -- 7       
insert into event_tag (tagname) values('Tech');                 -- 8
insert into event_tag (tagname) values('Streaming');           -- 9          


insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Python Workshop', 'In this course, you will learn the fundamentals of the Python programming language, along with programming best practices.'
,'2022-01-11', '2022-02-24', 'Online - Discord', 'Ongoing', 'https://i2.wp.com/idsc.miami.edu/wp-content/uploads/2020/10/Python-image-with-logo-940x530-1.jpg?resize=940%2C530&ssl=1', 5);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Movie Nights', 'The second edition of movie nights is here. This time we bring the Harry Potter Saga.'
,'2022-01-01', '2022-02-28', 'Online - Twitch', 'Ongoing', 'https://www.foothillsbaptist.org/wp-content/uploads/2016/01/movie-night-featured.jpg', 3);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('The Cooking Surviving Course','Are you that kind of person who does not know how to fry an egg? Dont worry we have the solution for you. In this course you will be able to learn the basics of cooking, in order to become capable to cook your meals and, whio knows, to impress your friends.'
, '2022-11-01', '2022-11-04', 'Online - Youtube', 'Scheduled', 'https://postarticles.org/wp-content/uploads/2021/05/cooking-at-home.png', 6);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Web Course for experts','Do you want to improve your web development skills? Enter here for learn the best practices as well as new techniques. Not recommended for people who have no experiece either with HTML, CSS and JavaScript!!'
, '2022-11-16', '2022-12-16', 'Online - Teams', 'Scheduled', 'https://miro.medium.com/max/3060/1*rGiHBnlqf6-koapA2DzUoA.jpeg',  5);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('CS:GO Tournament','Join your friends, make a team and get the ready for the best tournament.'
, '2022-07-10', '2022-07-17', 'Online - Steam', 'Scheduled', 'https://midias.jb.com.br/_midias/jpg/2021/03/11/csgo-586394.jpg',  7);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('CS:GO Tournament - Streaming','If you dont want to play, but are a big fan of the game you can enjoy the streaming of the tournament matches.'
, '2022-07-10', '2022-07-17', 'Online - Twitch', 'Scheduled', 'https://midias.jb.com.br/_midias/jpg/2021/03/11/csgo-586394.jpg', 9);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Comedy Nights - Porto',' Every weekend (Friday and Saturday) 3 comedians are invited to present you their best jokes. Although only 2 of them will be announced, the third one will be a surprise...'
, '2022-04-20', '2022-09-12', 'Porto - Comedy Bar', 'Scheduled', 'https://m.media-amazon.com/images/G/01/seo/siege-lists/best-comedy-audiobooks-collection-card._CB1579018750_.png', 4);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Movie Nights','After 2 incredible editions, the 3rd one is coming. We can confirm that all the Marvel Cinematic Universe movies are going to be on your screen.'
, '2022-05-01', '2022-08-31', 'Online - Twitch', 'Scheduled', 'https://www.foothillsbaptist.org/wp-content/uploads/2016/01/movie-night-featured.jpg', 3);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Yoga Workshop','We all have heard about yoga, but few os us have tried it. Enjoy now this unique lifestyle and improve your practices with this tips.'
, '2022-02-05', '2022-02-25', 'Aveiro', 'Scheduled', 'https://uploads.metropoles.com/wp-content/uploads/2019/01/08165756/WhatsApp-Image-2019-01-08-at-16.51.29.jpeg', 2);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('League of Legends Tournament','Prepare yourself for the best esports experience in this fully organized tournament. We have amazing prizes for the winners and a small gift for all participants.'
, '2022-07-10', '2022-07-17', 'Online', 'Scheduled', 'https://s2.glbimg.com/y6nngNdKtYKEZx9QiZIa-bW4cq4=/0x0:1200x675/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_08fbf48bc0524877943fe86e43087e7a/internal_photos/bs/2019/E/T/z4H0MFRxKrUlZijtEnAQ/20190522035739-1200-675-league-of-legends.jpg', 7);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('QuiZZ Tournament 2022','Test your knowlodge in this online quiz tournament. 3 modes available - individual, pairs, teams (4 elements). The prizes for each category are going to be announced soon. '
, '2022-11-14', '2022-12-02', 'Online - QuiZZ Official Website', 'Scheduled', 'https://img.freepik.com/fotos-gratis/quiz-ou-palavra-quizz-inscricao-jogo-divertido-com-perguntas_361816-1115.jpg?size=626&ext=jpg', 7);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Gadgets Unboxing and Review','Ever wondered if a online shop is reliable or not? Or being in doubt which product tp buy? Enjoy this sessiond with unboxings and reviews with from different websites and become a pro in online shopping.'
, '2022-09-1', '2022-10-20', 'Online - Youtube', 'Scheduled', 'http://revistaminha.pt/wp-content/uploads/2018/12/Gadgets.jpg', 8);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Summer Sunset 2.2','During all summer enjoy the best music with us.'
,'2022-07-01', '2022-08-31', 'Leça da Palmeira', 'Scheduled', 'https://cdn3.dpmag.com/2020/06/challenge-003-summer-sunset.jpg', 1);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('World Cup 2022 Streaming','Streaming of all matches of the 2022 Football World Cup'
,'2022-11-21', '2022-12-18', 'Online - Cloud Sports', 'Scheduled', 'https://www.marketplace.org/wp-content/uploads/2018/07/GettyImages-453347919.jpg?fit=1800%2C1000', 2);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Movie Nights','The first edition of movie nights is here. To celebrate the 4th of May we prepared  2 months of streaming of your favorite saga. Enjoy us and may the force be with you.'
,  '2021-05-04', '2021-07-07', 'Online - Twitch', 'Finished','https://www.foothillsbaptist.org/wp-content/uploads/2016/01/movie-night-featured.jpg', 3);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('QuiZZ Tournament 2021','Test your knowlodge in this online quiz tournament. 3 modes available - individual, pairs, teams (4 elements). The prizes for each category are going to be announced soon. '
, '2021-11-14', '2021-12-02', 'Online - QuiZZ Official Website', 'Finished', 'https://img.freepik.com/fotos-gratis/quiz-ou-palavra-quizz-inscricao-jogo-divertido-com-perguntas_361816-1115.jpg?size=626&ext=jpg', 7);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Summer Sunset 2.1',' During all summer enjoy the best music with us.'
,'2021-07-01', '2021-08-31', 'Leça da Palmeira', 'Finished', 'https://cdn3.dpmag.com/2020/06/challenge-003-summer-sunset.jpg', 1);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Euro 2020 - (2021 Edition) Streaming','After being postponed we maintain the streaming of all matches of the 2020 European Football Cup'
,'2021-06-11', '2021-07-11', 'Online - Cloud Sports', 'Finished', 'https://editorial.uefa.com/resources/0258-0e2236eb500b-4dac4439c8b9-1000/2502796.jpg', 2);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Rocket League Lan Party','Meet new people or just bring your friends to an incredible rocket league lan party.'
,  '2021-03-04', '2021-03-07', 'Porto', 'Canceled', 'https://cdn1.epicgames.com/offer/9773aa1aa54f4f7b80e44bef04986cea/6609d2e1-62d9-4094-9cb7-26d9a7f5ba3f_2560x1440-071db7b0d39d5635f684940c1e3c4ec3', 7);
insert into eventg (eventname, event_description, startdate, enddate, place, eventstate, pictureurl, tagid) values ('Euro 2020 Streaming','Streaming of all matches of the 2020 European Football Cup'
,'2020-06-10', '2020-07-10', 'Online - Cloud Sports', 'Canceled', 'https://editorial.uefa.com/resources/0258-0e2236eb500b-4dac4439c8b9-1000/2502796.jpg', 2);


insert into event_role (userid, eventid, ishost) values(6,1,true);      --1
insert into event_role (userid, eventid, ishost) values (16, 1, false);
insert into event_role (userid, eventid, ishost) values (26,1, false);
insert into event_role (userid, eventid, ishost) values (20, 1, false);
insert into event_role (userid, eventid, ishost) values (15, 1, false);
insert into event_role (userid, eventid, ishost) values (25, 1, false);

insert into event_role (userid, eventid, ishost) values(21,3,true);     --7
insert into event_role (userid, eventid, ishost) values (16, 3, false);
insert into event_role (userid, eventid, ishost) values (26, 3, false);
insert into event_role (userid, eventid, ishost) values (20, 3, false);
insert into event_role (userid, eventid, ishost) values (15,3, false);
insert into event_role (userid, eventid, ishost) values (25, 3, false);

insert into event_role (userid, eventid, ishost) values(18,4,true);    --13
insert into event_role (userid, eventid, ishost) values (16, 4, false);
insert into event_role (userid, eventid, ishost) values (26, 4, false);
insert into event_role (userid, eventid, ishost) values (20, 4, false);
insert into event_role (userid, eventid, ishost) values (15, 4, false);
insert into event_role (userid, eventid, ishost) values (25, 4, false);

insert into event_role (userid, eventid, ishost) values(25,7,true);    --19
insert into event_role (userid, eventid, ishost) values (12, 7, false);
insert into event_role (userid, eventid, ishost) values (15, 7, false);
insert into event_role (userid, eventid, ishost) values (22, 7, false);
insert into event_role (userid, eventid, ishost) values (20, 7, false);
insert into event_role (userid, eventid, ishost) values (8, 7, false);

insert into event_role (userid, eventid, ishost) values(10,9,true);   --25
insert into event_role (userid, eventid, ishost) values (9, 9, false);
insert into event_role (userid, eventid, ishost) values (11, 9, false);
insert into event_role (userid, eventid, ishost) values (29, 9, false);
insert into event_role (userid, eventid, ishost) values (20, 9, false);
insert into event_role (userid, eventid, ishost) values (8, 9, false);

insert into event_role (userid, eventid, ishost) values(9,10,true);   --31
insert into event_role (userid, eventid, ishost) values (13, 10, false);
insert into event_role (userid, eventid, ishost) values (11, 10, false);
insert into event_role (userid, eventid, ishost) values (29, 10, false);
insert into event_role (userid, eventid, ishost) values (20, 10, false);
insert into event_role (userid, eventid, ishost) values (8, 10, false);

insert into event_role (userid, eventid, ishost) values(15,19,true);   --37
insert into event_role (userid, eventid, ishost) values (9, 19, false);
insert into event_role (userid, eventid, ishost) values (11, 19, false);


insert into event_role (userid, eventid, ishost) values(17,12,true);
insert into event_role (userid, eventid, ishost) values (29, 12, false);
insert into event_role (userid, eventid, ishost) values (7, 12, false);
insert into event_role (userid, eventid, ishost) values (11, 12, false);
insert into event_role (userid, eventid, ishost) values (6, 12, false);
insert into event_role (userid, eventid, ishost) values (22, 12, false);
insert into event_role (userid, eventid, ishost) values (27, 12, false);

insert into event_role (userid, eventid, ishost) values(28,16,true);
insert into event_role (userid, eventid, ishost) values (16, 16, false);
insert into event_role (userid, eventid, ishost) values (6, 16, false);
insert into event_role (userid, eventid, ishost) values (11, 16, false);
insert into event_role (userid, eventid, ishost) values (30, 16, false);
insert into event_role (userid, eventid, ishost) values (26, 16, false);
insert into event_role (userid, eventid, ishost) values (27, 16, false);
insert into event_role (userid, eventid, ishost) values (10, 16, false);
insert into event_role (userid, eventid, ishost) values(28,11,true);
insert into event_role (userid, eventid, ishost) values (16, 11, false);
insert into event_role (userid, eventid, ishost) values (6, 11, false);
insert into event_role (userid, eventid, ishost) values (11, 11, false);
insert into event_role (userid, eventid, ishost) values (30, 11, false);
insert into event_role (userid, eventid, ishost) values (26, 11, false);
insert into event_role (userid, eventid, ishost) values (27, 11, false);
insert into event_role (userid, eventid, ishost) values (10, 11, false);



insert into event_role (userid, eventid, ishost) values(12,13,true);
insert into event_role (userid, eventid, ishost) values (22, 13, false);
insert into event_role (userid, eventid, ishost) values (23, 13, false);
insert into event_role (userid, eventid, ishost) values (6, 13, false);
insert into event_role (userid, eventid, ishost) values (24, 13, false);
insert into event_role (userid, eventid, ishost) values (30, 13, false);
insert into event_role (userid, eventid, ishost) values (9, 13, false);
insert into event_role (userid, eventid, ishost) values (18, 13, false);
insert into event_role (userid, eventid, ishost) values (26, 13, false);
insert into event_role (userid, eventid, ishost) values (27, 13, false);

insert into event_role (userid, eventid, ishost) values(12,17,true);
insert into event_role (userid, eventid, ishost) values (22, 17, false);
insert into event_role (userid, eventid, ishost) values (23, 17, false);
insert into event_role (userid, eventid, ishost) values (6, 17, false);
insert into event_role (userid, eventid, ishost) values (24, 17, false);
insert into event_role (userid, eventid, ishost) values (30, 17, false);
insert into event_role (userid, eventid, ishost) values (9, 17, false);
insert into event_role (userid, eventid, ishost) values (18, 17, false);
insert into event_role (userid, eventid, ishost) values (26, 17, false);
insert into event_role (userid, eventid, ishost) values (27, 17, false);

insert into event_role (userid, eventid, ishost) values(22,5,true);
insert into event_role (userid, eventid, ishost) values(24,5,true);
insert into event_role (userid, eventid, ishost) values (13, 5, false);
insert into event_role (userid, eventid, ishost) values (16, 5, false);
insert into event_role (userid, eventid, ishost) values (29, 5, false);
insert into event_role (userid, eventid, ishost) values (21, 5, false);
insert into event_role (userid, eventid, ishost) values (15, 5, false);
insert into event_role (userid, eventid, ishost) values (12, 5, false);
insert into event_role (userid, eventid, ishost) values (27, 5, false);
insert into event_role (userid, eventid, ishost) values (26, 5, false);
insert into event_role (userid, eventid, ishost) values (23, 5, false);
insert into event_role (userid, eventid, ishost) values(22,6,true);
insert into event_role (userid, eventid, ishost) values(24,6,true);
insert into event_role (userid, eventid, ishost) values (25, 6, false);
insert into event_role (userid, eventid, ishost) values (11, 6, false);
insert into event_role (userid, eventid, ishost) values (13, 6, false);
insert into event_role (userid, eventid, ishost) values (28, 6, false);
insert into event_role (userid, eventid, ishost) values (21, 6, false);
insert into event_role (userid, eventid, ishost) values (9, 6, false);

insert into event_role (userid, eventid, ishost) values(13,2,true);
insert into event_role (userid, eventid, ishost) values (21, 2, false);
insert into event_role (userid, eventid, ishost) values (27, 2, false);
insert into event_role (userid, eventid, ishost) values (15, 2, false);
insert into event_role (userid, eventid, ishost) values (22, 2, false);
insert into event_role (userid, eventid, ishost) values (9, 2, false);
insert into event_role (userid, eventid, ishost) values (29, 2, false);
insert into event_role (userid, eventid, ishost) values(13,8,true);
insert into event_role (userid, eventid, ishost) values (21, 8, false);
insert into event_role (userid, eventid, ishost) values (27, 8, false);
insert into event_role (userid, eventid, ishost) values (15, 8, false);
insert into event_role (userid, eventid, ishost) values (22, 8, false);
insert into event_role (userid, eventid, ishost) values (9, 8, false);
insert into event_role (userid, eventid, ishost) values (29, 8, false);
insert into event_role (userid, eventid, ishost) values(13,15,true);
insert into event_role (userid, eventid, ishost) values (21,15, false);
insert into event_role (userid, eventid, ishost) values (27,15, false);
insert into event_role (userid, eventid, ishost) values (15,15, false);
insert into event_role (userid, eventid, ishost) values (22,15, false);
insert into event_role (userid, eventid, ishost) values (9, 15, false);
insert into event_role (userid, eventid, ishost) values (29,15, false);


insert into event_role (userid, eventid, ishost) values(7,14,true); 
insert into event_role (userid, eventid, ishost) values(7,18,true); 
insert into event_role (userid, eventid, ishost) values(7,20,true); 
insert into event_role (userid, eventid, ishost) values(8,14,true); 
insert into event_role (userid, eventid, ishost) values(8,18,true); 
insert into event_role (userid, eventid, ishost) values(8,20,true); 
insert into event_role(userid,eventid,ishost) values (10,14,false); 
insert into event_role(userid,eventid,ishost) values (11,14,false); 
insert into event_role(userid,eventid,ishost) values (12,14,false); 
insert into event_role(userid,eventid,ishost) values (13,14,false); 
insert into event_role(userid,eventid,ishost) values (14,14,false); 
insert into event_role(userid,eventid,ishost) values (15,14,false); 
insert into event_role(userid,eventid,ishost) values (16,14,false); 
insert into event_role(userid,eventid,ishost) values (17,14,false); 
insert into event_role(userid,eventid,ishost) values (18,14,false); 
insert into event_role(userid,eventid,ishost) values (19,14,false); 
insert into event_role(userid,eventid,ishost) values (20,14,false); 
insert into event_role(userid,eventid,ishost) values (23,14,false); 
insert into event_role(userid,eventid,ishost) values (24,14,false); 
insert into event_role(userid,eventid,ishost) values (26,14,false); 
insert into event_role(userid,eventid,ishost) values (28,14,false); 
insert into event_role(userid,eventid,ishost) values (29,14,false); 
insert into event_role(userid,eventid,ishost) values (31,14,false); 
insert into event_role(userid,eventid,ishost) values (10,18,false); 
insert into event_role(userid,eventid,ishost) values (11,18,false); 
insert into event_role(userid,eventid,ishost) values (12,18,false); 
insert into event_role(userid,eventid,ishost) values (13,18,false); 
insert into event_role(userid,eventid,ishost) values (14,18,false); 
insert into event_role(userid,eventid,ishost) values (15,18,false); 
insert into event_role(userid,eventid,ishost) values (16,18,false); 
insert into event_role(userid,eventid,ishost) values (17,18,false); 
insert into event_role(userid,eventid,ishost) values (18,18,false); 
insert into event_role(userid,eventid,ishost) values (19,18,false); 
insert into event_role(userid,eventid,ishost) values (20,18,false); 
insert into event_role(userid,eventid,ishost) values (23,18,false); 
insert into event_role(userid,eventid,ishost) values (24,18,false); 
insert into event_role(userid,eventid,ishost) values (26,18,false); 
insert into event_role(userid,eventid,ishost) values (28,18,false); 
insert into event_role(userid,eventid,ishost) values (29,18,false); 
insert into event_role(userid,eventid,ishost) values (31,18,false); 
insert into event_role(userid,eventid,ishost) values (10,20,false); 
insert into event_role(userid,eventid,ishost) values (11,20,false); 
insert into event_role(userid,eventid,ishost) values (12,20,false); 
insert into event_role(userid,eventid,ishost) values (13,20,false); 
insert into event_role(userid,eventid,ishost) values (14,20,false); 
insert into event_role(userid,eventid,ishost) values (15,20,false); 
insert into event_role(userid,eventid,ishost) values (16,20,false); 
insert into event_role(userid,eventid,ishost) values (17,20,false); 
insert into event_role(userid,eventid,ishost) values (18,20,false); 
insert into event_role(userid,eventid,ishost) values (19,20,false); 
insert into event_role(userid,eventid,ishost) values (20,20,false); 
insert into event_role(userid,eventid,ishost) values (23,20,false); 
insert into event_role(userid,eventid,ishost) values (24,20,false); 
insert into event_role(userid,eventid,ishost) values (26,20,false); 
insert into event_role(userid,eventid,ishost) values (28,20,false); 
insert into event_role(userid,eventid,ishost) values (29,20,false); 
insert into event_role(userid,eventid,ishost) values (31,20,false); 


insert into invite (receiverid, senderid, eventid) values (30, 6, 1);
insert into invite (receiverid, senderid, eventid) values (1, 21, 3);
insert into invite (receiverid, senderid, eventid) values (28, 25, 7);
insert into invite (receiverid, senderid, eventid) values (2, 10, 9);
insert into invite (receiverid, senderid, eventid) values (10, 9, 10);


insert into ask_access (participant, eventid) values (25, 12);
insert into ask_access (participant, eventid) values (23, 17);
insert into ask_access (participant, eventid) values (12, 5);
insert into ask_access (participant, eventid) values (19, 8);
insert into ask_access (participant, eventid) values (27, 9);


insert into event_announcement (messagea, role_id) values ('Unfortunately there arent enough participants to continue with the initial plans. So the saddest decision was made and the event is canceled.', 37);
insert into event_announcement (messagea, role_id) values ('Aware of the latest news, we are forced to call off all streams. As soon as new dates are established, a new event will be created. ', 127);
insert into event_announcement (messagea, role_id) values ('The Euro 2020 is back, dont miss any match.', 126);
insert into event_announcement (messagea, role_id) values ('Winners: Individual - sortigag', 47);
insert into event_announcement (messagea, role_id) values ('Winners: Pairs - bleitche5, sclackersrr', 47);
insert into event_announcement (messagea, role_id) values ('Winners: Teams - kdonovina, rmanachr, saery9, hbuscombeh', 47);
insert into event_announcement (messagea, role_id) values ('First announcements Friday: Rui Sinel de Cordes and Rui Cruz', 19);
insert into event_announcement (messagea, role_id) values ('Saturday: Hugo Sousa and Carlos Vidal', 19);
insert into event_announcement (messagea, role_id) values ('Due to some external error we have to postpone stream of the first movie. As soon as the problem is fixed we will inform you', 111);
insert into event_announcement (messagea, role_id) values ('The problem is now fixed. The new date is 2021-05-07', 111);
insert into event_announcement (messagea, role_id) values ('Prizes: 1st - 500$ voucher; 2nd -  250$; 3rd - 100$', 85);
insert into event_announcement (messagea, role_id) values ('Note: all prizes previously announced are team prizes and NOT individual prizes.', 85);
insert into event_announcement (messagea, role_id) values ('The tournament is going to start soon. We will publish the scheduled when all the teams confirm match dates.', 96);
insert into event_announcement (messagea, role_id) values ('Due to the world pandemic situation all participants need to use a mask during all event. Also you can only enter with a valid EU COVID Digital Certificate (vaccination, testing or recovery).', 74);




insert into event_comment (messagec, role_id, photo) values ('Are there any Covid-19 restrictions??', 78, NULL);
insert into event_comment (messagec, role_id, photo) values ('You have the answer in the announcements.', 79, NULL);
insert into event_comment (messagec, role_id, photo) values ('How many times each movie is going to be streamed?', 104, NULL);
insert into event_comment (messagec, role_id, photo) values ('How many times each movie is going to be streamed?', 111, NULL);
insert into event_comment (messagec, role_id, photo) values ('How many times each movie is going to be streamed?', 120, NULL);
insert into event_comment (messagec, role_id, photo) values ('Ronaldo > Messi', 151, NULL);
insert into event_comment (messagec, role_id, photo) values ('Messi > Ronaldo', 152, NULL);
insert into event_comment (messagec, role_id, photo) values ('Goat Lewangoalski', 153, NULL);


insert into event_poll (messagep, role_id) values ('Should this event be cancelled?', 1);
insert into event_poll (messagep, role_id) values ('Do you have all the ingredients?', 7);
insert into event_poll (messagep, role_id) values ('Do you know PHP?', 13);
insert into event_poll (messagep, role_id) values ('Are you ready to pee your pants from laughing too hard?', 19);
insert into event_poll (messagep, role_id) values ('Do not forget your yoga pants :)', 25);
insert into event_poll (messagep, role_id) values ('Who do you thin will win this competition?', 31);



insert into poll_option (messagepo, pollid, countvote) values ('Yes', 1, 5);
insert into poll_option (messagepo, pollid, countvote) values ('No', 1, 7);
insert into poll_option (messagepo, pollid, countvote) values ('Yes', 2, 8);
insert into poll_option (messagepo, pollid, countvote) values ('No', 2, 2);
insert into poll_option (messagepo, pollid, countvote) values ('Yes', 3, 5);
insert into poll_option (messagepo, pollid, countvote) values ('No', 3, 4);
insert into poll_option (messagepo, pollid, countvote) values ('Already brought a diaper', 4, 9);
insert into poll_option (messagepo, pollid, countvote) values ('I am gonna find out', 4, 2);
insert into poll_option (messagepo, pollid, countvote) values ('Going to Decathlon', 5, 3);
insert into poll_option (messagepo, pollid, countvote) values ('Already using them', 5, 5);
insert into poll_option (messagepo, pollid, countvote) values ('BalckKnight365', 6, 12);
insert into poll_option (messagepo, pollid, countvote) values ('Assassin23', 6, 4);


insert into vote (votetype, event_roleid, commentid, announcementid) values (true, 10, 2, NULL);
insert into vote (votetype, event_roleid, commentid, announcementid) values (false, 1, 8, NULL);
insert into vote (votetype, event_roleid, commentid, announcementid) values (true, 3, 8, NULL);
insert into vote (votetype, event_roleid, commentid, announcementid) values (false, 5, 7, NULL);
insert into vote (votetype, event_roleid, commentid, announcementid) values (true, 14, 7, NULL);
insert into vote (votetype, event_roleid, commentid, announcementid) values (false, 5, NULL, 6);
insert into vote (votetype, event_roleid, commentid, announcementid) values (false, 13, NULL, 10);
insert into vote (votetype, event_roleid, commentid, announcementid) values (true, 10, NULL, 3);
insert into vote (votetype, event_roleid, commentid, announcementid) values (false, 7, NULL, 12);
insert into vote (votetype, event_roleid, commentid, announcementid) values (false, 12, NULL, 4);



insert into reports (userid, eventid, descriptions) values (6,17,'This event should be cancelled due to the increase of COVID cases.');