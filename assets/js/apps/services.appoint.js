toggleAlertAppoint = function (title, msg, c){
	$('div[data-name="alert-appoint"]').removeClass().addClass(c);
	$('div[data-name="alert-appoint"] h4').html(title);
	$('div[data-name="alert-appoint"] p').html(msg);
}
	
$(function() {
	$('input[data-name="appoint_date"]').datepicker({dateFormat: 'd/m/yy'});
	
	$('input[data-name="appoint_diag_name"]').autocomplete({
		source: function(request, response){
			$.ajax({
	            url: _base_url + 'basic/search_diag',
	            dataType: 'json',
	            type: 'POST',
	            data: {
	                query: request.term,
	                csrf_token: $.cookie('csrf_cookie_cloudhis')
	            },
	            success: function(data){
	                response($.map(data, function(i){
	                    return {
	                        label: i.code + ' ' + i.name,
	                        value: i.name,
	                        code: i.code
	                    }
	                }));
	            }
       	 	});
		},
		minLength: 2,
		select: function(event, ui){
			$('input[data-name="appoint_diag_code"]').val(ui.item.code);
		}
	});
	// remove appointment
	$('a[data-name="remove-appoint"]').live('click', function() {
		var _id = $(this).attr("data-appoint"),
				_vn = $('input[data-name="vn"]').val();
					
		if ( confirm( 'คุณต้องการลบรายการนี้ใช่หรือไม่?' )  ) {
			$(this).parent().parent().remove();
			doRemoveAppoint( _vn, _id);
		}
	} );
	
	// check drug detail
	$( 'button[data-name="btn-save-appoint"]' ).click( function() {
		var _vn 						= $('input[data-name="vn"]').val(),
		_appoint_id 				= $('select[data-name="appoint_id"]').val(),
		_appoint_date 			= $('input[data-name="appoint_date"]').val(),
		_appoint_diag_code	= $('input[data-name="appoint_diag_code"]').val();
		
		if( ! _vn ) {
			toggleAlertAppoint('กรุณาตรวจสอบข้อมูล', 'ไม่พบ <code> รหัสการให้บริการ (VN) </code>',  'alert alert-error');
		} else if( ! _appoint_id ) {
			toggleAlertAppoint('กรุณาตรวจสอบข้อมูล', 'ไม่พบ<code>กิจกรรมที่นัด</code>',  'alert alert-error');
		} else if( ! _appoint_date ) {
			toggleAlertAppoint('กรุณาตรวจสอบข้อมูล', 'ไม่พบ<code>วันที่นัด</code>',  'alert alert-error');
		} else if( ! _appoint_diag_code ) {
			toggleAlertAppoint('กรุณาตรวจสอบข้อมูล', 'ไม่พบ<code>รหัสการวินิจฉัย</code>',  'alert alert-error');
		}  else { // save drug
			doSaveAppoint( _vn, _appoint_id, _appoint_date, _appoint_diag_code );
		}
	} );
	// do save drug
	var doSaveAppoint = function( _vn, _appoint_id, _appoint_date, _appoint_diag_code ) {
		// do save
		$.ajax({
			url: _base_url + 'services/doappoint',
			dataType: 'json',
			type: 'POST',
			data: {
				csrf_token: $.cookie('csrf_cookie_cloudhis'),
				vn: _vn,
				appoint_id: _appoint_id,
				appoint_date: _appoint_date,
				appoint_diag: _appoint_diag_code
			},
			success: function(data){
				if(data.success){
					toggleAlertAppoint(' บันทึกข้อมูล',  ' บันทึกข้อมูลเสร็จเรียบร้อยแล้ว', 'alert alert-success');
					$('button[data-name="btnreset"]').click();
					//update appoint list
					getAppointmentList();
					//$('div#modal-appoint').modal('hide');
				}else{
					toggleAlertAppoint('เกิดข้อผิดพลาด!', 'เกิดข้อผิดพลาดในการส่งข้อมูล:  ' + data.status,  'alert alert-error');
				}
			  
			},
			error: function(xhr, status, errorThrown){
				toggleAlertAppoint('เซิร์ฟเวอร์มีปัญหา!', 'เกิดข้อผิดพลาดในการส่งข้อมูล  : <code>' +  xhr.status + ': ' + xhr.statusText + '</code>' ,'alert alert-error');
		  }	
		});// ajax
	}// end do save drug
	, doRemoveAppoint = function( _vn, _id) {
		$.ajax({
			url: _base_url + 'services/removeappoint',
			dataType: 'json',
			type: 'POST',
			data: {
				csrf_token: $.cookie('csrf_cookie_cloudhis'),
				vn: _vn,
				id: _id
			},
			success: function(data){
				if(data.success){
					toggleAlertAppoint(' ผลการลบ',  ' ลบรายการที่ไม่ต้องการเรียบร้อยแล้ว ', 'alert alert-success');
					// refresh appointment list
					getAppointmentList();
				}else{
					toggleAlertAppoint('เกิดข้อผิดพลาด!', 'เกิดข้อผิดพลาดในการส่งข้อมูล:  ' + data.status,  'alert alert-error');
				}
			  
			},
			error: function(xhr, status, errorThrown){
				toggleAlert('เซิร์ฟเวอร์มีปัญหา!', 'เกิดข้อผิดพลาดในการส่งข้อมูล  : <code>' +  xhr.status + ': ' + xhr.statusText + '</code>' ,'alert alert-error');
		  }	
		});// ajax
	};// end doRemoveDrug
	// get appointment list
	var getAppointmentList = function() {
		var _cid = $('input[data-name="cid"]').val();
		
		$.ajax({
			url: _base_url + 'services/getappoint',
			dataType: 'json',
			type: 'POST',
			data: {
				csrf_token: $.cookie('csrf_cookie_cloudhis'),
				cid: _cid
			},
			success: function( data ) {
				if( data.success ) {
					//console.log(data.rows);
					$('table[data-name="tblAppointList"] > tbody').empty();
					
					$.each(data.rows, function(i, v){
						$('table[data-name="tblAppointList"] > tbody').append(
							'<tr>' 
							+ '<td>' + toThaiDate( v.date_serv ) + '</td>'
							+ '<td>' + toThaiDate( v.appoint_date ) + '</td>'
							+ '<td>' + v.appoint_name  + '</td>'
							+ '<td>' + v.appoint_diag + ' ' + v.diag_name + '</td>' 
							+ '<td><a href="#" data-name="remove-appoint" data-appoint="' + v.id + '" class="btn" title="ลบทิ้ง"><i class="icon-trash"></i></a></td>'
							+ '</tr>'
						);
					});
				} else {
					alert( data.status );
				}
			},
			error: function(xhr, status, errorThrown) {
				alert('ไม่สามารถแสดงข้อมูลการนัดได้: [ '  + xhr.status + ' ' + xhr.statusText +' ]')
			}
		});
	}
	
	$('a[data-name="show-appoint"]').click( function() {
		getAppointmentList();
	});
//
});