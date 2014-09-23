$(document).ready(function(){
  $('#guwen').addClass('guwen_on');
  $('#hezuo_main').hide();
  $("#guwen").mouseover(function () {
    $(this).addClass('guwen_on');
	$("#hezuo").removeClass ('hezuo_on')
    $('#guwen_main').show();
    $('#hezuo_main').hide();
   });
  $("#hezuo").mouseover(function () {
    $(this).addClass('hezuo_on');
	$("#guwen").removeClass ('guwen_on')
    $('#guwen_main').hide();
    $('#hezuo_main').show();
   });

  $('#lady').addClass('lady_on');
  $('#man_main').hide();
  $("#lady").mouseover(function () {
    $(this).addClass('lady_on');
	$("#man").removeClass ('man_on')
    $('#lady_main').show();
    $('#man_main').hide();
   });
  $("#man").mouseover(function () {
    $(this).addClass('man_on');
	$("#lady").removeClass ('lady_on')
    $('#lady_main').hide();
    $('#man_main').show();
   });
    $('#gaoji').hide();
  $("#vip_z").mouseover(function () {
    $(this).removeClass('vip_zon');
	$("#vip_g").removeClass ('vip_gon')
    $('#gaoji').hide();
    $('#zuanshi').show();
   });

  $("#vip_g").mouseover(function () {
    $(this).addClass('vip_gon');
	$("#vip_z").addClass ('vip_zon')
    $('#zuanshi').hide();
    $('#gaoji').show();
   });


});



