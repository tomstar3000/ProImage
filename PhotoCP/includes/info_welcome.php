
<h1 id="HdrType2" class="RcntOrd">
  <div>Recent Orders</div>
</h1>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <th>Order Number</th>
        <th>Date</th>
        <th>Event Id</th>
        <th>Event Code</th>
        <th>Client</th>
        <th>Status</th>
        <th class="R">Total Sales</th>
      </tr>
      <? foreach($getOrders->Rows() as $k => $r){
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getOrders->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getOrders->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getOrders->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onmouseover="this.className='<? echo $class2; ?>';" onmouseout="this.className='<? echo $class1; ?>';" onclick="javascript:set_form('','Busn,Open,<? echo $r['invoice_id']; ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" style="cursor:pointer;">
        <td><? echo $r['invoice_num']; ?></td>
        <td><? echo format_date($r['invoice_date'],"Dash",false,true,false); ?></td>
        <td><? echo $r['event_id']; ?></td>
        <td><? echo $r['event_num']; ?></td>
        <td><? echo $r['cust_fname']." ".$r['cust_lname']; ?></td>
        <td><? switch($r['invoice_accepted']){
					case 'n': echo "Waiting Approval"; break;
					case 'y':
					case 'p': echo "Sent to Lab"; break;
					/*case 'y':
						switch($r['invoice_comp']){
							case 'y': echo "Shipped"; break;
							default:
								switch($r['invoice_printed']){
									case 'y': echo "Printed"; break;
									case 'n': echo "Printing"; break;
								} break;		
						} break;	
					*/
				} ?></td>
        <td class="R">$<? echo number_format($r['invoice_grand'],2,".",","); ?></td>
      </tr>
      <? } ?>
    </table>
    <div id="Ftr">
      <div id="BtnType1" class="S116">
        <input type="button" name="FullOrderHistory" id="FullOrderHistory" title="View Full Order History" onmouseover="window.status='View Full Order History'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Busn,Open','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" value="Full Order History" />
      </div>
      <p id="Total">Requiring Approval:<strong><? echo $getOrdersCnt; ?></strong></p>
    </div>
  </div>
  <div id="Bottom"></div>
</div>
<h1 id="HdrType2" class="NewsEvents">
  <div>Pro. Image News &amp; Updates</div>
</h1>
<div id="RecordTable" class="Yellow">
  <div id="Top"></div>
  <div id="Records">
    <h1>Photo Express Updates!</h1>
    <? foreach($getUpdates->Rows() as $r) echo $r['page_text_text']; ?>
    <div id="Ftr">
      <div id="BtnType1" class="S116">
        <input type="button" name="View Updates" id="ViewUpdates" title="View Updates" value="View Updates" onmouseover="window.status='View Updates'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Comm,Update','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" />
      </div>
      <div id="BtnType1" class="S100"></div>
    </div>
  </div>
  <div id="Bottom"></div>
</div>