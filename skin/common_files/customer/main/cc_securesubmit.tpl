<span class="securesubmit_checkout_errors"></span>

<table cellspacing="0" class="data-table">
	{if $hide_header ne "Y"}
		<tr>
			<td class="register-section-title" colspan="3">
				<a name="ccinfo"></a>
				<label>Payment Information</label>
			</td>
		</tr>
	{/if}

	<tr>
		<td class="data-name"><label for="credit_card_card_number">Card Number</label></td>
		<td class="data-required">*</td>
		<td><input type="text" id="credit_card_card_number" autocomplete="off" size="30" maxlength="20" value="" /></td>
	</tr>

	<tr>
		<td class="data-name"><label for="credit_card_expiry_month">Expiration Date</label></td>
		<td class="data-required">*</td>
		<td>
			<select id="credit_card_expiry_month">
				<option value=""></option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
			</select>
			<select id="credit_card_expiry_year">
				<option value=""></option>
				<option value="2013">2013</option>
				<option value="2014">2014</option>
				<option value="2015">2015</option>
				<option value="2016">2016</option>
				<option value="2017">2017</option>
				<option value="2018">2018</option>
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				<option value="2021">2021</option>
				<option value="2022">2022</option>
				<option value="2023">2023</option>
				<option value="2024">2024</option>
				<option value="2025">2025</option>
				<option value="2026">2026</option>
				<option value="2027">2027</option>
				<option value="2028">2028</option>
				<option value="2029">2029</option>
				<option value="2030">2030</option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="data-name"><label for="credit_card_cvv">CVV</label></td>
		<td class="data-required">*</td>
		<td><input type="text" id="credit_card_cvv" autocomplete="off" size="30" maxlength="4" value="" /></td>
	</tr>
</table>

<input type="hidden" class="securesubmit_token" id="securesubmit_token" name="securesubmit_token" value="" />

<script>
	var securesubmit_key = '{''|publickey}';
</script>
<script type="text/javascript" src="/skin/common_files/js/secure.submit-1.0.2.js"></script>
<script type="text/javascript" src="/skin/common_files/js/securesubmit.js"></script>
