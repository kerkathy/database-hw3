<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<!-- refer to boostrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<title>Home</title>
<style>
.error {color: #FF0000;}
</style>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
tr:nth-child(even) {
  background-color: #dddddd;
}
</style>


</head>


<body>

<div class="container">
<a href="home.php">Home</a>
<span>&nbsp&nbsp</span>
<a href="clf_owner.php">Shop</a>
<span>&nbsp&nbsp</span>
<a href="my_order.php">My Order</a>
<span>&nbsp&nbsp</span>
<a href="shop_order.php">Shop Order</a>
<span>&nbsp&nbsp</span>
<a href="index.php" onclick="logout()">Logout</a>
<br><br>

<script type="text/javascript">
	function logout(){
		<?php session_destroy();  ?>
	}
</script>

<script type="text/javascript">
  var stsclick = 0;
  var stcclick = 0;
  var stpclick = 0;
  var staclick = 0;
	function sortTableShop() {
	  var table, rows, switching, i, x, y, shouldSwitch;
	  table = document.getElementById("ShopList");
	  switching = true;
	  /*Make a loop that will continue until
	  no switching has been done:*/
	  while (switching) {
	    //start by saying: no switching is done:
	    switching = false;
	    rows = table.rows;
	    /*Loop through all table rows (except the
	    first, which contains table headers):*/
	    for (i = 1; i < (rows.length - 1); i++) {
	      //start by saying there should be no switching:
	      shouldSwitch = false;
	      /*Get the two elements you want to compare,
	      one from current row and one from the next:*/
	      x = rows[i].getElementsByTagName("TD")[0];
	      y = rows[i + 1].getElementsByTagName("TD")[0];
	      //check if the two rows should switch place:
				if(stsclick % 2 == 0){
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
  	        //if so, mark as a switch and break the loop:
  	        shouldSwitch = true;
  	        break;
  	      }
        }
        else{
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
  	        //if so, mark as a switch and break the loop:
  	        shouldSwitch = true;
  	        break;
  	      }
        }
	    }
	    if (shouldSwitch) {
	      /*If a switch has been marked, make the switch
	      and mark that a switch has been done:*/
	      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
	      switching = true;
	    }
	  }
	}
	function sortTableCity() {
	  var table, rows, switching, i, x, y, shouldSwitch;
	  table = document.getElementById("ShopList");
	  switching = true;
	  while (switching) {
	    switching = false;
	    rows = table.rows;
	    for (i = 1; i < (rows.length - 1); i++) {
	      shouldSwitch = false;
	      x = rows[i].getElementsByTagName("TD")[1];
	      y = rows[i + 1].getElementsByTagName("TD")[1];
	      if(stcclick % 2 == 0){
          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
  	        shouldSwitch = true;
  	        break;
  	      }
        }
        else{
          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
  	        shouldSwitch = true;
  	        break;
  	      }
        }
	    }
	    if (shouldSwitch) {
	      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
	      switching = true;
	    }
	  }
	}
	function sortTableMaskPrice() {
	  var table, rows, switching, i, x, y, shouldSwitch;
	  table = document.getElementById("ShopList");
	  switching = true;
	  while (switching) {
	    switching = false;
	    rows = table.rows;
	    for (i = 1; i < (rows.length - 1); i++) {
	      shouldSwitch = false;
	      x = rows[i].getElementsByTagName("TD")[2];
	      y = rows[i + 1].getElementsByTagName("TD")[2];
	      if(stpclick % 2 == 0){
          if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
  	        shouldSwitch = true;
  	        break;
  	      }
        }
        else{
          if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
  	        shouldSwitch = true;
  	        break;
  	      }
        }
	    }
	    if (shouldSwitch) {
	      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
	      switching = true;
	    }
	  }
	}
	function sortTableMaskAmount() {
	  var table, rows, switching, i, x, y, shouldSwitch;
	  table = document.getElementById("ShopList");
	  switching = true;
	  while (switching) {
	    switching = false;
	    rows = table.rows;
	    for (i = 1; i < (rows.length - 1); i++) {
	      shouldSwitch = false;
	      x = rows[i].getElementsByTagName("TD")[3];
	      y = rows[i + 1].getElementsByTagName("TD")[3];
	      if(staclick % 2 == 0){
          if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
  	        shouldSwitch = true;
  	        break;
  	      }
        }
        else{
          if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
  	        shouldSwitch = true;
  	        break;
  	      }
        }
	    }
	    if (shouldSwitch) {
	      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
	      switching = true;
	    }
	  }
	}


  function filter(){
	var sinput, sfilter, table, tr, td0, i, stxtValue;
  sinput = document.getElementById("shop_input");
  sfilter = sinput.value.toUpperCase();
  table = document.getElementById("ShopList");
  tr = table.getElementsByTagName("tr");

  var pinput1, pinput2, pfilter1, pfilter2, table, tr, td2, i, ptxtValue;
  pinput1 = document.getElementById("price_input1");
  pinput2 = document.getElementById("price_input2");
  if(pinput1.value == null || pinput1.value == undefined || pinput1.value == ''){
    pfilter1 = 0;
  }
  else{
    pfilter1 = parseInt(pinput1.value);
  }
  if(pinput2.value == null || pinput2.value == undefined || pinput2.value == ''){
    pfilter2 = 99999999999;
  }
  else{
    pfilter2 = parseInt(pinput2.value);
  }

  var cfilter, table, tr, td1, i, ctxtValue;
  var cy, cs;
  cy = document.getElementById("city_input");
  cs = cy.options[cy.options.selectedIndex].value;

  var table, tr, td3, i, atxtValue;
  var ay, as;
  ay = document.getElementById("amount_input");
  as = ay.options[ay.options.selectedIndex].value;

  var finput, ffilter, table, tr, td0, i, ftxtValue;
  finput = document.getElementById("myshop");
  ffilter = finput.value;

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
  	td0 = tr[i].getElementsByTagName("td")[0];
  	td1 = tr[i].getElementsByTagName("td")[1];
    td2 = tr[i].getElementsByTagName("td")[2];
    td3 = tr[i].getElementsByTagName("td")[3];
    if (td0) {
    	stxtValue = td0.textContent || td0.innerText;
      ptxtValue = td2.textContent || td2.innerText;
      ctxtValue = td1.textContent || td1.innerText;
      atxtValue = td3.textContent || td3.innerText;
      ftxtValue = td0.textContent || td0.innerText;
      if (parseInt(ptxtValue) >= pfilter1 && parseInt(ptxtValue) <= pfilter2 && stxtValue.toUpperCase().indexOf(sfilter) > -1 && (ctxtValue == cs || cs == "init") && (ftxtValue.indexOf(ffilter) > -1 || finput.checked == false) ) {
        if(as == "init"){
          tr[i].style.display = "";
        }
        else if(as == "sold_out"){
          if (parseInt(atxtValue) == 0) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
        else if(as == "rare"){
          if (parseInt(atxtValue) >= 1 && parseInt(atxtValue) <= 99) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
        else if(as == "sufficient"){
          if (parseInt(atxtValue) >= 100) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
      else {
        tr[i].style.display = "none";
      }
    }
  }


}

</script>
