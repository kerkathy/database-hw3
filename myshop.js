$(document).ready(function() {
	$("#priceBtn").click(function() {
		if ($(this).text() == "Edit") {
			$("#price").attr("disabled", false);
			$("#priceBtn").text("Send");
		}
		else {
			$.ajax({
				// url:"/modify_shop.php",
				url:"modify_shop.php",
				data:{ "price": $("#price").val() },
				method:"POST",
				type:"POST",
				dataType: 'json',
				// error: function(jqxhr, status, reason) {
	   //              alert('error: ' + reason);
				// },
				success: function(response) {
					var jsonData = JSON.parse(response);
					// alert('returned from php');
					// alert(jsonData.success);
					// if(data.success == '1') alert('Update mask price successfully.');
					// else alert('Update mask price failed. ')
				}
			});
			$("#price").attr("disabled", true);
			$("#priceBtn").text("Edit");
		}
	});

	$("#amountBtn").click(function(){
		if ($(this).text() == "Edit"){
			$("#amount").attr("disabled", false);
			$("#amountBtn").text("Send");
		}
		else {
			$.ajax({
				url:"modify_shop.php",
				data:{ "amount": $("#amount").val() },
				method:"POST",
				type:"POST",
				dataType: 'json',
				success:function(data) {
					if(data.success == "1") alert("Update mask amount successfully.");
					// if(data.success > 0) alert('Update mask amount successfully.');
					else alert('Update mask amount failed. ')
				}
			});
			$("#amount").attr("disabled", true);
			$("#amountBtn").text("Edit");
		}
	});
});