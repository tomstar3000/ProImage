<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Pricing", true); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrLabPrice"><span>Lab Cost to Photographer</span></h1>
      <ul class="ProSubNav">
        <!-- <li><a href="/pricing/" title="Plan Details &amp; Full Features">Plan Details &amp; Full Features</a> </li> -->
        <li><a href="/pricing/lab_pricing.php" title="Lab Pricing">Lab Pricing</a> </li>
        <li><a href="/pricing/faq.php" title="FAQ">FAQ</a></li>
      </ul>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom"><br clear="all" />
          <table cellspacing="0" cellpadding="2" border="1" bordercolor="#999999"  style="margin-left:auto; margin-right:auto; border-collapse:collapse; width:700px">
            <col width="158" />
            <col width="110" span="2" />
            <col width="110" span="3" />
           
            <tr>
              <th rowspan="2">Size
                </td>
              <th rowspan="2">Print
                </td>
              <th>Canvas
                </td>
              <th colspan="3">Canvas - Stretched
                </td>
            </tr>
            <tr>
              <th>Unstretched
                </td>
              <th>1&quot; Stretch
                </td>
              <th>1.5&quot; Stretch
                </td>
              <th>2&quot; Stretch
                </td>
            </tr>
            <tr>
              <td><strong>Low Res Digital File</strong></td>
              <td>$1.50</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>High Res    Digital File</strong></td>
              <td>$2.50</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>2 Wallets</strong></td>
              <td>$1.65</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>8 Wallets</strong></td>
              <td>$4.20</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>24 Wallets</strong></td>
              <td>$7.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>48 Wallets</strong></td>
              <td>$11.20</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>80 Wallets</strong></td>
              <td>$14.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>3.5x5</strong></td>
              <td>$0.95</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>4x5</strong></td>
              <td>$0.95</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>4x6</strong></td>
              <td>$0.95</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(10) 4x6</strong></td>
              <td>$2.66</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(25) 4x6</strong></td>
              <td>$5.51</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(100) 4x6</strong></td>
              <td>$19.76</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>5x7</strong></td>
              <td>$1.65</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(10) 5x7</strong></td>
              <td>$7.50</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(25) 5x7</strong></td>
              <td>$17.25</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>8x10</strong></td>
              <td>$4.20</td>
              <td>$24.75</td>
              <td>$48.75</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>8x12</strong></td>
              <td>$4.70</td>
              <td>$29.75</td>
              <td>$49.25</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>11x14</strong></td>
              <td>$8.20</td>
              <td>$23.50</td>
              <td>$69.50</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>12x12</strong></td>
              <td>$8.20</td>
              <td>$31.50</td>
              <td>$62.25</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>12x18</strong></td>
              <td>$10.50</td>
              <td>$32.95</td>
              <td>$71.50</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>16x20</strong></td>
              <td>$20.90</td>
              <td>$49.00</td>
              <td>$98.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>16x24</strong></td>
              <td>$31.80</td>
              <td>$61.00</td>
              <td>&nbsp;</td>
              <td>$115.00</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>20x20</strong></td>
              <td>$32.95</td>
              <td>$61.00</td>
              <td>&nbsp;</td>
              <td>$116.00</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>20x30</strong></td>
              <td>$45.65</td>
              <td>$91.50</td>
              <td>&nbsp;</td>
              <td>$144.00</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>24x30</strong></td>
              <td>$52.85</td>
              <td>$99.00</td>
              <td>&nbsp;</td>
              <td>$163.00</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>24x36</strong></td>
              <td>$67.60</td>
              <td>$112.00</td>
              <td>&nbsp;</td>
              <td>$176.00</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>30x30</strong></td>
              <td>$67.60</td>
              <td>$116.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>$189.00</td>
            </tr>
            <tr>
              <td><strong>30x40</strong></td>
              <td>$68.75</td>
              <td>$138.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>$230.00</td>
            </tr>
            <tr>
              <td><strong>(10) 5x7    Cards/Envelope</strong></td>
              <td>$9.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(25) 5x7    Cards/Envelope</strong></td>
              <td>$22.50</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(50) 5x7    Cards/Envelope</strong></td>
              <td>$40.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(75) 5x7    Cards/Envelope</strong></td>
              <td>$57.75</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(100) 5x7    Cards/Envelope</strong></td>
              <td>$66.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(200) 5x7    Cards/Envelope</strong></td>
              <td>$120.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(10) 4x8    Cards/Envelope</strong></td>
              <td>$9.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(25) 4x8    Cards/Envelope</strong></td>
              <td>$22.50</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(50) 4x8    Cards/Envelope</strong></td>
              <td>$40.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(75) 4x8    Cards/Envelope</strong></td>
              <td>$57.75</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(100) 4x8    Cards/Envelope</strong></td>
              <td>$66.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><strong>(200) 4x8    Cards/Envelope</strong></td>
              <td>$120.00</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <br clear="all" />
        </div>
      </div>
      <br clear="all" />
    </div>
  </div>
  <? include $r_path.'includes/_footer.php'; ?>
</div>
</body>
</html>
