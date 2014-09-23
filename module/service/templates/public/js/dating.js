$(function(){
		var salary = '';
		var city = '';
		var dating_choice1 = $('#dating_choice1');
		var dating_choice2 = $('#dating_choice2');
		var desc1 = $('#desc1');
		var desc2 = $('#desc2');
		dating_choice1.bind('click',function(){
			$(this).hide();
			dating_choice2.show();
			desc1.hide();
			desc2.show();
		})
		
		dating_choice2.bind('click',dating_partner)
		
		function dating_partner(){
			var province = $('#workProvince option:selected').text();
			var city_obj = $('#workCity option:selected');
			if(city_obj.val() != '0'){
				city = city_obj.text();
			}
			var age1 = $('#dating_age1 option:selected');
			var age2 = $('#dating_age2 option:selected');
			if(age1.val()=='0' || age2.val()=='0'){
				alert('请选择年龄');
				return ;
			}
			var salary_obj = $('#salary option:selected');
			if(salary_obj.val() != '0'){
				salary = ',收入在'+salary_obj.text();
			}
			var marriage2 = $('#marriage2 option:selected').text();
			var dating_sex = $('#dating_sex').text();
			dating_desc = '希望您是'+province+city+age1.text()+'岁-'+age2.text()+'岁 '+salary+'的'+marriage2+dating_sex+'性。';
			
			$("#dating_partner").text(dating_desc);
			//desc1.text(dating_desc);
			$(this).hide();
			dating_choice1.show();
			desc1.show();
			desc2.hide();
		}
		
		dating_partner();
	})