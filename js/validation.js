$(document).ready(function(){
	var customerName = $("#customerName");
	var customerNameInfo = $("#customerNameInfo");
	var price = $("#price");
	var priceInfo = $("#priceInfo");
	var cardHolderName = $("#cardHolderName");
	var cardHolderNameInfo = $("#cardHolderNameInfo");
	var cardNumber = $("#cardNumber");
	var cardNumberInfo = $("#cardNumberInfo");
	var expireMonth = $("#expireMonth");
	var expireMonthInfo = $("#expireMonthInfo");
	var cvv = $("#cvv");
	var cvvInfo = $("#cvvInfo");
	
	//Validate payment form
	$("#paymentForm").submit(function(){
		var _validateName = validateName();
		var _validatePrice = validatePrice();
		var _validateCardHolderName = validateCardHolderName();
		var _validateCardNumber = validateCardNumber();
		var _validateExpireMonth = validateExpireMonth();
		var _validateCvv = validateCvv();
		
		if(_validateName && _validatePrice && _validateCardHolderName && _validateCardNumber && _validateExpireMonth && _validateCvv){
			return true;
		}
		else{
			return false;
		}				
		
		return false;
	});
	
	//On blur events
	customerName.keyup(validateName);
	price.keyup(validatePrice);
	cardHolderName.keyup(validateCardHolderName);
	cardNumber.keyup(validateCardNumber);
	expireMonth.change(validateExpireMonth);
	cvv.keyup(validateCvv);
	
	//validate functions
	function validateName(){
		
		var testVal = $.trim(customerName.val());
		var filter = /^[a-zA-Z\s]+$/;
		if(filter.test(testVal) && testVal.length >= 3){
			customerName.removeClass("error");
			customerNameInfo.text("");
			customerNameInfo.removeClass("error");
			return true;			
		}else{
			customerName.addClass("error");
			customerNameInfo.text("Please enter name with atleast 3 characters");
			customerNameInfo.addClass("error");
			return false;
		}
	}
	
	function validatePrice(){
		
		var testVal = $.trim(price.val());
		var filter = /^[\d\.]+$/;
		if(filter.test(testVal)){
			price.removeClass("error");
			priceInfo.text("");
			priceInfo.removeClass("error");
			return true;			
		}else{
			price.addClass("error");
			priceInfo.text("Please enter a amount in numbers");
			priceInfo.addClass("error");
			return false;
		}
	}
	
	function validateCardHolderName(){
		
		var testVal = $.trim(cardHolderName.val());
		var filter = /^[a-zA-Z\s]+$/;
		if(filter.test(testVal) && testVal.length >= 3){
			cardHolderName.removeClass("error");
			cardHolderNameInfo.text("");
			cardHolderNameInfo.removeClass("error");
			return true;			
		}else{
			cardHolderName.addClass("error");
			cardHolderNameInfo.text("Please enter name with atleast 3 characters");
			cardHolderNameInfo.addClass("error");
			return false;
		}
	}
	
	function validateCardNumber(){
		
		var testVal = $.trim(cardNumber.val());
		var filter = /^[\d\.]+$/;
		if(filter.test(testVal) && (testVal.length == 16 || testVal.length == 15)){
			cardNumber.removeClass("error");
			cardNumberInfo.text("");
			cardNumberInfo.removeClass("error");
			return true;			
		}else{
			cardNumber.addClass("error");
			cardNumberInfo.text("Please enter a valid card number");
			cardNumberInfo.addClass("error");
			return false;
		}
	}
	
	function validateExpireMonth(){
		
		var testVal = $.trim(expireMonth.val());
		if(testVal != ""){
			expireMonth.removeClass("error");
			expireMonthInfo.text("");
			expireMonthInfo.removeClass("error");
			return true;			
		}else{
			expireMonth.addClass("error");
			expireMonthInfo.text("Please select a month");
			expireMonthInfo.addClass("error");
			return false;
		}
	}
	
	function validateCvv(){
		
		var testVal = $.trim(cvv.val());
		var filter = /^[\d\.]+$/;
		if(filter.test(testVal) && (testVal.length == 3 || testVal.length == 4)){
			cvv.removeClass("error");
			cvvInfo.text("");
			cvvInfo.removeClass("error");
			return true;			
		}else{
			cvv.addClass("error");
			cvvInfo.text("Please enter a valid CVV");
			cvvInfo.addClass("error");
			return false;
		}
	}
	
});