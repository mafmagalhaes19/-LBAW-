function setup_popup_btn(close,id){
  const close_popup_btn = document.getElementById(close);
  var popup_container = document.getElementById(id);
  close_popup_btn.addEventListener('click', function() {
    popup_container.style.visibility = "hidden";
  });
}