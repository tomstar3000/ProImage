<table border="0" cellpadding="5" cellspacing="0" width="100%">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="Change Login Form" id="Change_Login_Form" method="post" enctype="application/x-www-form-urlencoded">
    <?php if($Error){
		  	print("<tr><td colspan=\"3\">".$Error."</td></tr>\n<tr>\n<td colspan=\"3\"><hr size=\"1\" color=\"#FFFFFF\"></td>\n</tr>\n");
		  } ?>
    <tr>
      <th colspan="3" id="Cart_Header"><p>Enter you Log-in Information</p></th>
    </tr>
    <tr>
      <td>Username:</td>
      <td colspan="2"><input type="text" name="Username" id="Username" tabindex="1"></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td colspan="2"><input type="password" name="Password" id="Password" tabindex="2"></td>
    </tr>
    <tr>
      <td colspan="3"><hr size="1" color="#FFFFFF"></td>
    </tr>
    <tr>
      <th colspan="3" id="Cart_Header"><p>New Login or Password</p></th>
    </tr>
    <!-- Enter in new user-name and password -->
    <tr>
      <td>New Username: </td>
      <td><input type="text" name="New Username" id="New_Username" maxlength="25" tabindex="3"></td>
      <td>No spaces</td>
    </tr>
    <tr>
      <td>New Password:</td>
      <td><input type="password" name="New Password" id="New_Password" maxlength="15" tabindex="4"></td>
      <td rowspan="2">4-15 lower case characters and no spaces</td>
    </tr>
    <tr>
      <td>Confirm Password:</td>
      <td><input type="password" name="Confirm Password" id="Confirm_Password" maxlength="15" tabindex="5"></td>
    </tr>
    <!-- 
    <tr>
      <td colspan="3" class="bar"><img src="/images/spacer.gif" width="1" height="1"></td>
    </tr>
    <tr>
      <th colspan="3" align="center" id="Cart_Header"><p>Security Question </p></th>
    </tr>
    <tr>
      <td>Security Question: </td>
      <td colspan="2"><select name="Security Question" id="Security_Question" tabindex="17">
          <?php //do { ?>
          <option value="<?php //echo $row_get_security_questions['question_id']; ?>"><?php //echo $row_get_security_questions['question']; ?></option>
          <?php //} while ($row_get_security_questions = mysql_fetch_assoc($get_security_questions)); ?>
        </select></td>
    </tr>
    <tr>
      <td>Answer:</td>
      <td colspan="2"><input type="text" name="Answer" id="Answer" tabindex="18"></td>
    </tr>
    <tr>
      <td colspan="3" class="bar"><img src="/images/spacer.gif" width="1" height="1"></td>
    </tr>
	-->
    <tr>
      <td colspan="3" align="center"><p>
          <input type="hidden" name="Controller" id="Controller" value="true">
          <input type="submit" id="Submit" name="Submit" value="Submit" onClick="javascript:MM_validateForm('Username','','R','Password','','R'); return document.MM_returnValue;">
        </p></td>
    </tr>
  </form>
</table>
