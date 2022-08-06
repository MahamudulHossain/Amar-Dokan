jQuery('#formRegId').on('submit',function(e){
  jQuery('#register_submit').attr('disabled',true);
  jQuery('.reg_error').html('');
  jQuery('#email_success').html('Please Wait...');
  jQuery.ajax({
    url : "login-register-form.php",
    type : "post",
    data : jQuery('#formRegId').serialize(),
    success : function(result){
      jQuery('#email_success').html('');
      var data = jQuery.parseJSON(result);
      if(data.status == 'register_error'){
        jQuery('#reg_success').html('');
        jQuery('#register_submit').attr('disabled',false);
        jQuery("#"+data.field).html(data.msg);
      }
      if(data.status == 'register_success'){
        jQuery("#email_success").html(data.msg);
        jQuery('#formRegId')[0].reset();
      }
    }
  });
  e.preventDefault();
});


jQuery('#form_login').on('submit',function(e){
	jQuery('#success_msg').html('');
	jQuery('#login_submit').attr('disabled',true);
	jQuery('#success_msg').html('Please wait...');
	jQuery.ajax({
		url:'login-register-form.php',
		type:'post',
		data:jQuery('#form_login').serialize(),
		success:function(result){
			jQuery('#success_msg').html('');
			jQuery('#login_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='login_error'){
				jQuery('#success_msg').html(data.msg);
			}
      var is_checkout = jQuery("#is_checkout").val();
      if(is_checkout == "checkout"){
        window.location.href='checkout.php';
      }else if(data.status=='login_success'){
				window.location.href='shop.php';
			}
		}

	});
	e.preventDefault();
});


jQuery('#form_forget_password').on('submit',function(e){
	jQuery('#success_msg').html('');
	jQuery('#forgot_submit').attr('disabled',true);
	jQuery('#success_msg').html('Please wait...');
	jQuery.ajax({
		url:'login-register-form.php',
		type:'post',
		data:jQuery('#form_forget_password').serialize(),
		success:function(result){
			jQuery('#success_msg').html('');
			jQuery('#forgot_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='login_error'){
				jQuery('#success_msg').html(data.msg);
			}
      if(data.status=='login_success'){
				jQuery('#success_msg').html(data.msg);
			}
		}

	});
	e.preventDefault();
});


function cat_check(id){
  var cat_check_id = jQuery("#cat_check_id").val();
  var cat_check = cat_check_id.search(":"+id);
  if(cat_check != '-1'){
    cat_check_id = cat_check_id.replace(":"+id,'');
  }else{
    cat_check_id = cat_check_id + ":"+id;
  }
  jQuery("#cat_check_id").val(cat_check_id);
  jQuery("#check_box")[0].submit();
}

function add_cart(id,type){
  var qty = jQuery("#qty_"+id).val();
  var attr = jQuery('input[name="radio_'+id+'"]:checked').val();
  if(typeof attr === 'undefined'){
    var is_radio_checked = 'no';
  }
  if(qty>0 && is_radio_checked!='no'){
    jQuery.ajax({
      url : 'manage_cart.php',
      type : 'post',
      data : 'qty='+qty+'&attr='+attr+'&type='+type,
      success : function(result){
        var data = jQuery.parseJSON(result);
        jQuery('#totalCartProduct').html(data.totalCartProduct);
        jQuery('#totalCartPrice').html(data.totalCartPrice+"Tk.");
        jQuery('#added_cart_id_'+attr).html('Added - ('+qty+')');
        swal("Congratulation!", "Your Product Is Added Into Cart ", "success");
        var totalCartPrice = data.totalCartPrice;
        if(data.totalCartProduct==1){
          var tp = data.price * qty;
          var html = '<div class="shopping-cart-content"><ul id="cart_ul"><li class="single-shopping-cart" id="cart_product_id_'+attr+'"><div class="shopping-cart-img"><a href="javascript:void(0)"><img alt="" src="'+SITE_IMAGE_PATH+data.image+'"></a></div><div class="shopping-cart-title"><h4><a href="javascript:void(0)">'+data.product+'</a></h4><h6>Qty: '+qty+'</h6><span>'+tp+'Tk.</span></div><div class="shopping-cart-delete"><a href="javascript:void(0)" onclick=delete_from_cart('+attr+')><i class="ion ion-close"></i></a></div></li></ul><div class="shopping-cart-total"><h4>Total : <span class="shop-total">'+totalCartPrice+'Tk.</span></h4></div><div class="shopping-cart-btn"><a href="cart.php">view cart</a><a href="checkout.php">checkout</a></div></div>';
          jQuery('.header-cart').append(html);
        }else{
          var tp1 = data.price * qty;
          jQuery("#cart_product_id_"+attr).remove();
          var html1 = '<li class="single-shopping-cart" id="cart_product_id_'+attr+'"><div class="shopping-cart-img"><a href="javascript:void(0)"><img alt="" src="'+SITE_IMAGE_PATH+data.image+'"></a></div><div class="shopping-cart-title"><h4><a href="#">'+data.product+'</a></h4><h6>Qty: '+qty+'</h6><span>'+tp1+'Tk.</span></div><div class="shopping-cart-delete"><a href="javascript:void(0)" onclick=delete_from_cart('+attr+')><i class="ion ion-close"></i></a></div></li>';
          jQuery('#cart_ul').append(html1);
          jQuery('.shop-total').html(totalCartPrice+'Tk');
        }
      }
    });
  }else{
    swal("Error!", "Select product details correctly", "error");
  }
}


function delete_from_cart(id,is_load){
  jQuery.ajax({
    url : 'manage_cart.php',
    type : 'post',
    data : 'attr='+id+'&type=delete',
    success : function(result){
      if(is_load == 'load'){
        window.location.href = window.location.href;
      }else{
        var data = jQuery.parseJSON(result);
        jQuery('#totalCartProduct').html(data.totalCartProduct);
        jQuery('#totalCartPrice').html(data.totalCartPrice+"Tk.");
        jQuery('.shop-total').html(data.totalCartPrice+'Tk');
        jQuery('#added_cart_id_'+id).html('');
        if(data.totalCartProduct == 0){
          jQuery('#totalCartPrice').html('');
          jQuery('.shopping-cart-content').remove();
        }else{
          jQuery("#cart_product_id_"+id).remove();
        }
      }
    }
  });
}

jQuery('#frmMyAccount').on('submit',function(e){
	jQuery('#success_pro_msg').html('');
	jQuery('#profile_submit').attr('disabled',true);
	jQuery('#success_pro_msg').html('Please wait...');
	jQuery.ajax({
		url:'profile-cngpass-form.php',
		type:'post',
		data:jQuery('#frmMyAccount').serialize(),
		success:function(result){
			jQuery('#success_pro_msg').html('');
			jQuery('#profile_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
			if(data.status=='pro_success'){
        jQuery("#acc_name").html(jQuery('#pro_name').val());
				swal("Update Status", "Your Account Is Updated", "success");
			}
		}
	});
	e.preventDefault();
});

jQuery('#frmChngPass').on('submit',function(e){
	jQuery('#success_pass_msg').html('');
	jQuery('#pass_submit').attr('disabled',true);
	jQuery('#success_pass_msg').html('Please wait...');
	jQuery.ajax({
		url:'profile-cngpass-form.php',
		type:'post',
		data:jQuery('#frmChngPass').serialize(),
		success:function(result){
			jQuery('#success_pass_msg').html('');
			jQuery('#pass_submit').attr('disabled',false);
			var data=jQuery.parseJSON(result);
      if(data.status == 'pass_fail'){
        jQuery('#success_pass_msg').html(data.msg);
      }
      if(data.status=='pass_success'){
				swal("Update Status", "Your Password Is Updated", "success");
			}
		}
	});
	e.preventDefault();
});

function coupon_code_apply(){
  var cupon_code = jQuery("#cupon_code").val();
  if(cupon_code == ''){
    jQuery('.coupon_msg').html('Please enter coupon code');
  }else{
    jQuery('.coupon_msg').html('');
    jQuery.ajax({
      url:'coupon_code_apply.php',
  		type:'post',
  		data:'cupon_code='+cupon_code,
  		success:function(result){
  			var data=jQuery.parseJSON(result);
        if(data.status == 'success'){
          jQuery(".coupon_code_name").html(cupon_code);
          jQuery(".final_price").html(data.final_pay+"Tk.");
          jQuery(".coupon_code_apply_box").show();
          swal("Success",data.msg, "success");
        }
        if(data.status == 'error'){
  				swal("Error",data.msg, "error");
  			}
  		}
    });
  }
}
