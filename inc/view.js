function change_view_mode() {
  var form=document.getElementById("view");

  var view_mode=form.mode.value;

  var item=document.getElementById("item");
  $(item).removeClass("mode_symbols mode_list mode_details");
  $(item).addClass("mode_"+view_mode);

  ajax("set_view", { 'mode': view_mode }, function(result) { });
}
