$('document').ready(function(){

	
	initStars();

    $('body').on('submit', '.ajax-form', function(e){
		var form = $(this);	
		e.preventDefault();
		e.stopPropagation();
		
		if (!form.hasClass('sending')) {
			form.addClass('sending');
			$(form).ajaxSubmit({
				dataType: 'json',
				success: function(data){


					if (data.error != undefined){
						if (data.message != undefined) {
							if ( data.message == 'without_notice' )
								form.removeClass('sending');
							else if (data.message != '')
								setNotice(data.message, (data.error == 0) ? 'success' : 'warning');


						} else {
							setNotice((data.error == 0) ? 'Отправлено' : 'Ошибка', (data.error == 0) ? 'success' : 'warning');
						}

						if (data.error == 0) {
							if (data.reload != undefined){
								location.reload()
							}	
							if (data.redirect != undefined){
								location.href = data.redirect;
							} if (data.callback != undefined){
								window[data.callback](data);
							} else {
								if (!form.hasClass('notreset')) {
									form[0].reset();
									resetStars();
									$(`#rating_department option`).prop('selected', false);
									$(`#rating_department option[value="-"]`).prop('selected', true);
									$(`#rating_employee option`).prop('selected', false);
									$(`#rating_employee option[value="-"]`).prop('selected', true);
									$.ajax({
										type: "POST",
										url: "../../actions/GetoptionsAction.php",
										data: {id: 0},
										success: function(data){

											$('.select_employee').html(data);
										}
									});
								}
							}

						}
					}

				},
				complete: function(response){
					
					setTimeout(function(){
						form.removeClass('sending');
						
					}, 1000);

				},
				error: function(requestObject, error, errorThrown){
					setSystemErrorReport({
						link: form.attr('action'),
						text: requestObject.responseText
					});
					setNotice('Системная ошибка', 'warning');
				}
			})
		}
	});

	$('.select_department').change(function(){

		var id = $(this).val();
		if ( id == 0 ) {

		}

		$.ajax({
			type: "POST",
			url: "../../actions/GetoptionsAction.php",
			data: {id: id},
			success: function(data){

				$('.select_employee').html(data);
			}
		});
	});

	$('body').on('submit', '.result_form', function(e){

		e.preventDefault();
		e.stopPropagation();
		var department = $('.result_form #rating_department').val(),
			employee = $('.result_form #rating_employee').val(),
			from_time = $('.result_form .from_time').val(),
			to_time = $('.result_form .to_time').val();


		$.ajax({
			type: "POST",
			url: "../../actions/GetresultAction.php",
			data: {department: department, employee: employee, from_time: from_time, to_time: to_time},
			success: function(data){

				$('.result_block').html(data);
			}
		});
	});

	$('.del_department').click( function(e){

		e.preventDefault();
		e.stopPropagation();
		id = $(this).attr('data-id');

		$.ajax({
			type: "POST",
			url: "../../actions/DepartmentAction.php",
			data: {id: id, action: 'delete'},
			success: function(data){
				location.reload();
			}
		});
	});

	$('.edit_department').click( function(e){

		e.preventDefault();
		e.stopPropagation();
		var id = $(this).attr('data-id'),
			name = $(this).attr('data-name');

		$('.department_form .submit').val('Өзгерту');
		$('.department_form .id').val(id);
		$('.department_form .department').val(name);
		
	});

	$('.department_form').submit( function(e){

		e.preventDefault();
		e.stopPropagation();

		var id = $('.department_form .id').val(),
			department = $('.department_form .department').val();

		if ( id == -1 ) {
			$.ajax({
				type: "POST",
				url: "../../actions/DepartmentAction.php",
				data: {department: department, action: 'add'},
				success: function(data){
					location.reload()
				}
			});
		} else {
			$.ajax({
				type: "POST",
				url: "../../actions/DepartmentAction.php",
				data: {id: id, department: department, action: 'edit'},
				success: function(data){
					location.reload()
				}
			});
		}

		
	});

	$('body').on('submit', '.employee_form', function(e){

		e.preventDefault();
		e.stopPropagation();
		var department = $('.employee_form #rating_department').val();

		$.ajax({
			type: "POST",
			url: "../../actions/GetemployeesAction.php",
			data: {department: department},
			success: function(data){
				$('.employee_block').html(data);
				$(`#rating_department2 option[value=${department}]`).prop('selected', true);
			}
		});
	});

	$('body').on('click', '.del_employee', function(e){

		e.preventDefault();
		e.stopPropagation();
		id = $(this).attr('data-id');

		$.ajax({
			type: "POST",
			url: "../../actions/EmployeeAction.php",
			data: {id: id, action: 'delete'},
			success: function(data){
				$('.employee_form').submit();
			}
		});
	});

	$('body').on('click', '.edit_employee', function(e){

		e.preventDefault();
		e.stopPropagation();
		var id = $(this).attr('data-id'),
			name = $(this).attr('data-name'),
			department = $(this).attr('data-department');

		$('.employee_form_add .submit').val('Өзгерту');
		$('.employee_form_add .id').val(id);
		$('.employee_form_add .employee').val(name);
		$(`#rating_department2 option[value=${department}]`).prop('selected', true);
		
	});

	$('body').on('submit', '.employee_form_add', function(e){

		e.preventDefault();
		e.stopPropagation();

		var id = $('.employee_form_add .id').val(),
			employee = $('.employee_form_add .employee').val(),
			department_id = $('.employee_form_add #rating_department2').val();

		if ( department_id == '-' ) {
			setNotice('Бөлімді таңдаңыз', 'warning');
		} else {

			if ( id == -1 ) {
				$.ajax({
					type: "POST",
					url: "../../actions/EmployeeAction.php",
					data: {employee: employee, department_id: department_id, action: 'add'},
					success: function(data){
						$('.employee_form').submit();
					}
				});
			} else {
				$.ajax({
					type: "POST",
					url: "../../actions/EmployeeAction.php",
					data: {id: id, employee: employee, department_id: department_id, action: 'edit'},
					success: function(data){
						console.log(data);
						$('.employee_form').submit();
					}
				});
			}

		}
		
	});

	$('body').find('.js_date').each(function(){
			var el = $(this);
			if (el.hasClass('alldate')) {
				el.datetimepicker({
					timepicker:false,
					format:'d.m.Y',
					lang:'ru',
					closeOnDateSelect: true,
					//minDate: '-1970/01/01',
					defaultDate:new Date(),
					dayOfWeekStart:1,
				});
			} else {
				el.datetimepicker({
					timepicker:false,
					format:'d.m.Y',
					lang:'ru',
					closeOnDateSelect: true,
					minDate: '-1970/01/01',
					defaultDate:new Date(),
					dayOfWeekStart:1,			
				});
			}
		});

})

function setNotice(mess, theme, delay, position) {

	if (delay == undefined) {
		delay = 6000;
	}
	if (position == undefined) {
		position = 'top';
	}

	var options = {
		appendTo: "body",
		customClass: 'cstm_notice',
		type: "info",
		offset:
		{
		   from: position,
		   amount: 30
		},
		align: "right",
		minWidth: 250,
		maxWidth: 400,
		delay: delay,
		allowDismiss: true,
		spacing: 10
	}

	if ($.simplyToast != undefined) {
		$.simplyToast(mess, theme, options);
	} else {
		alert(mess);
	}
}

function initStars() {
	var Loadblock = $('body');

	if (window.rating != undefined) {
       Loadblock.find('.c-rating:not(.init)').each(function(){

            var input_rating = $(this).find('input.rating_val');
            var readonly = false;
            if ($(this).attr('data-readonly') != undefined &&  $(this).attr('data-readonly'))
                readonly = true;

            $(this).addClass('init');
            window.rating(this, input_rating.val() , 5, function(rating){
                input_rating.val(rating);
            });

        })
    }
}

function resetStars() {

	$('.c-rating__item').remove();
	$('body').find('.c-rating.init').removeClass('init');
	$('input.rating_val').val('0');
	initStars()

}





