/**
 * Kumpulan custom function yang dapat digunakan 
 * Syarat: 
 * 	harus diawali dengan 'incf' , agar programmer lain tahu bahwa function tersebut didapatkan dari file in-custom-format.js
 * 
 */

function incfIsEmail(email) {
	/**
	 * digunakan untuk melakukan pengecekan apakah variable merupakan email atau bukan
	 */
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!regex.test(email)) {
		return false;
	}else{
		return true;
	}
}

function incfFormatNumberWithDecimal(number, decimalDigits) {
	// Convert the number to a float (if it's a string)
	number = parseFloat(number);

	// Check if the number is valid and not NaN
	if (isNaN(number)) {
	  return 'Invalid number';
	}
  
	// Check if decimalDigits is valid and non-negative
	decimalDigits = Number.isInteger(decimalDigits) && decimalDigits >= 0 ? decimalDigits : 2;
  
	// Convert the number to a string with the specified decimal digits
	let formattedNumber = number.toFixed(decimalDigits);
  
	// Add thousands separators
	formattedNumber = formattedNumber.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  
	return formattedNumber;
}