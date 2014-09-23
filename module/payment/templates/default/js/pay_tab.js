$(document).ready(function(){

//标题
  //$(".pay_one").addClass("click_p")
  //$("#debit_card").hide();
   
  $(".pay_two").addClass("click_p")
  $("#credit").hide();


  $(".pay_one").click(function () {
	$(this).addClass("click_p");
	$(".pay_two").removeClass("click_p");
	$("#credit").show();

	$("#debit_card").hide();
  });
  $(".pay_two").click(function () {
	$(this).addClass("click_p");
	$(".pay_one").removeClass("click_p");
	$("#credit").hide();
	$("#debit_card").show();
  });





});