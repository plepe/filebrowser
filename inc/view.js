function change_view_mode() {
  var form=document.getElementById("view");

  var view_mode=form.mode.value;

  var item=document.getElementById("item");
  $(item).removeClass("mode_symbols mode_list");
  $(item).addClass("mode_"+view_mode);
}