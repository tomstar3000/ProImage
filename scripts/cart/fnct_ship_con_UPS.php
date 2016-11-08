<? 
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
define ("Allow Script", true);
include ($pathing.'scripts/cart/security.php');
require_once($r_path.'scripts/fnct_xml_parser.php');
function connect_ship_UPS($width,$height,$depth,$weight,$speed,$AccessNum,$User,$PWord,$Name,$Contact,$TaxId,$Phone,$Fax,$Shipper,$PostZip,$PostCount,$ToZip,$ToCount,$FromZip,$FromCount){
	
	//$socket = "https://wwwcie.ups.com/ups.app/xml/Rate";
	$socket = "https://www.ups.com/ups.app/xml/Rate";

	switch($speed){
		case "1": //Next Day Air® Early A.M.
			$speed = "14";
			break;
		case "2": //Next Day Air
			$speed = "01";
			break;
		case "3": //Next Day Air Saver
			$speed = "13";
			break;
		case "4": //2nd Day Air A.M.
			$speed = "59";
			break;
		case "5": //2nd Day Air
			$speed = "02";
			break; 
		case "6": //3 Day Select
			$speed = "12";
			break;
		case "7": //Ground
			$speed = "03";
			break;
		default:
			$speed = "03";
	}
	$xml = '<?xml version="1.0"?>
	<AccessRequest xml:lang="en-US">
		<AccessLicenseNumber>'.$AccessNum.'</AccessLicenseNumber>
		<UserId>'.$User.'</UserId>
		<Password>'.$PWord.'</Password>
	</AccessRequest>
	<?xml version="1.0"?>
	<RatingServiceSelectionRequest xml:lang="en-US">
		<Request>
			<TransactionReference>
				<CustomerContext>Bare Bones Rate Request</CustomerContext>
				<XpciVersion>1.0001</XpciVersion>
			</TransactionReference>
			<RequestAction>Rate</RequestAction>
			<RequestOption>Rate</RequestOption>
		</Request>
		<PickupType>
			<Code>01</Code>
		</PickupType>
		<Shipment>
			<Shipper>
				<Name>'.$Name.'</Name>
				<AttentionName>'.$Contact.'</AttentionName>
				<TaxIdentificationNumber>'.$TaxId.'</TaxIdentificationNumber>
				<PhoneNumber>'.$Phone.'</PhoneNumber>
				<FaxNumber>'.$Fax.'</FaxNumber>
				<ShipperNumber>'.$Shipper.'</ShipperNumber>
				<Address>
					<PostalCode>'.$PostZip.'</PostalCode>
					<CountryCode>'.$PostCount.'</CountryCode>
				</Address>
			</Shipper>
			<ShipTo>
				<Address>
					<PostalCode>'.$ToZip.'</PostalCode>
					<CountryCode>'.$ToCount.'</CountryCode>
				</Address>
			</ShipTo>
			<ShipFrom>
				<Address>
					<PostalCode>'.$FromZip.'</PostalCode>
					<CountryCode>'.$FromCount.'</CountryCode>
				</Address>
			</ShipFrom>
			<Service>
				<Code>'.$speed.'</Code>
			</Service>
			<Package>
				<PackagingType>
					<Code>02</Code>
				</PackagingType>
				<Dimensions>
					<UnitOfMeasurement>
						<Code>IN</Code>
					</UnitOfMeasurement>
					<Length>'.$depth.'</Length>
					<Width>'.$width.'</Width>
					<Height>'.$height.'</Height>
				</Dimensions>
				<PackageWeight>
					<UnitOfMeasurement>
						<Code>LBS</Code>
					</UnitOfMeasurement>
					<Weight>'.$weight.'</Weight>
				</PackageWeight>
			</Package>
		</Shipment>
	</RatingServiceSelectionRequest>';
	
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $socket);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$responce = curl_exec ($ch);
	curl_close ($ch);
	$parser = new XMLParser($responce, 'raw', 1);
	$tree = $parser->getTree();
	
	/* <?xml version="1.0"?>
	<RatingServiceSelectionResponse>
		<Response>
			<TransactionReference>
				<CustomerContext>Bare Bones Rate Request</CustomerContext>
				<XpciVersion>1.0001</XpciVersion>
			</TransactionReference>
			<ResponseStatusCode>1</ResponseStatusCode>
			<ResponseStatusDescription>Success</ResponseStatusDescription>
		</Response>
		<RatedShipment>
			<Service><code>03</code></Service>
			<RatedShipmentWarning>Your invoice may vary from the displayed reference
				rates</RatedShipmentWarning>
			<BillingWeight>
				<UnitOfMeasurement><code>LBS</code></UnitOfMeasurement>
				<Weight>42.0</Weight>
			</BillingWeight>
			<TransportationCharges>
				<CurrencyCode>USD</CurrencyCode>
				<MonetaryValue>27.24</MonetaryValue>
			</TransportationCharges>
			<ServiceOptionsCharges>
				<CurrencyCode>USD</CurrencyCode>
				<MonetaryValue>0.00</MonetaryValue>
			</ServiceOptionsCharges>
			<TotalCharges>
				<CurrencyCode>USD</CurrencyCode>
				<MonetaryValue>27.24</MonetaryValue>
			</TotalCharges>
			<GuaranteedDaysToDelivery></GuaranteedDaysToDelivery>
			<ScheduledDeliveryTime></ScheduledDeliveryTime>
			<RatedPackage>
				<TransportationCharges>
					<CurrencyCode>USD</CurrencyCode>
					<MonetaryValue>27.24</MonetaryValue>
				</TransportationCharges>
				<ServiceOptionsCharges>
					<CurrencyCode>USD</CurrencyCode>
					<MonetaryValue>0.00</MonetaryValue>
				</ServiceOptionsCharges>
				<TotalCharges>
					<CurrencyCode>USD</CurrencyCode>
					<MonetaryValue>27.24</MonetaryValue>
				</TotalCharges>
				<Weight>23.0</Weight>
				<BillingWeight>
					<UnitOfMeasurement><code>LBS</code></UnitOfMeasurement>
					<Weight>42.0</Weight>
				</BillingWeight>
			</RatedPackage>
		</RatedShipment>
	</RatingServiceSelectionResponse> 
	// Error
	Array ( 
		[RATINGSERVICESELECTIONRESPONSE] => Array ( 
			[RESPONSE] => Array ( 
				[TRANSACTIONREFERENCE] => Array ( 
					[CUSTOMERCONTEXT] => Array ( 
						[VALUE] => Bare Bones Rate Request 
					)
					[XPCIVERSION] => Array ( 
						[VALUE] => 1.0001 
					)
				)
				[RESPONSESTATUSCODE] => Array (
					[VALUE] => 0 
				)
				[RESPONSESTATUSDESCRIPTION] => Array (
					[VALUE] => Failure
				)
				[ERROR] => Array (
					[ERRORSEVERITY] => Array (
						[VALUE] => Hard
					)
					[ERRORCODE] => Array (
						[VALUE] => 10002
					)
					[ERRORDESCRIPTION] => Array (
						[VALUE] => The XML document is well formed but the document is not valid
					)
					[ERRORLOCATION] => Array (
						[ERRORLOCATIONELEMENTNAME] => Array (
							[VALUE] => AccessRequest/AccessLicenseNumber
						)
					)
				)
			)
		)
	) 
	
	// Accepted
	Array(
		[RATINGSERVICESELECTIONRESPONSE] => Array(
			[RESPONSE] => Array(
				[TRANSACTIONREFERENCE] => Array(
					[CUSTOMERCONTEXT] => Array(
						[VALUE] => Bare Bones Rate Request
					)
					[XPCIVERSION] => Array(
						[VALUE] => 1.0001
					)
				)
				[RESPONSESTATUSCODE] => Array(
					[VALUE] => 1
				)
				[RESPONSESTATUSDESCRIPTION] => Array(
					[VALUE] => Success
				)
			)
			[RATEDSHIPMENT] => Array(
				[SERVICE] => Array(
					[CODE] => Array(
						[VALUE] => 03
					)
				)
				[RATEDSHIPMENTWARNING] => Array(
					[VALUE] => Your invoice may vary from the displayed reference rates
				)
				[BILLINGWEIGHT] => Array(
					[UNITOFMEASUREMENT] => Array(
						[CODE] => Array(
							[VALUE] => LBS
						)
					)
					[WEIGHT] => Array(
						[VALUE] => 42.0
					)
				)
				[TRANSPORTATIONCHARGES] => Array(
					[CURRENCYCODE] => Array(
						[VALUE] => USD
					)
					[MONETARYVALUE] => Array(
						[VALUE] => 27.24
					)
				)
				[SERVICEOPTIONSCHARGES] => Array(
					[CURRENCYCODE] => Array(
						[VALUE] => USD
					)
					[MONETARYVALUE] => Array(
						[VALUE] => 0.00
					)
				)
				[TOTALCHARGES] => Array(
					[CURRENCYCODE] => Array(
						[VALUE] => USD
					)
					[MONETARYVALUE] => Array(
						[VALUE] => 27.24
					)
				)
				[GUARANTEEDDAYSTODELIVERY] => Array(
					[VALUE] => 
				)
				[SCHEDULEDDELIVERYTIME] => Array(
					[VALUE] =>
				)
				[RATEDPACKAGE] => Array(
					[TRANSPORTATIONCHARGES] => Array(
						[CURRENCYCODE] => Array(
							[VALUE] => USD
						)
						[MONETARYVALUE] => Array(
							[VALUE] => 27.24
						)
					)
					[SERVICEOPTIONSCHARGES] => Array(
						[CURRENCYCODE] => Array(
							[VALUE] => USD
						)
						[MONETARYVALUE] => Array(
							[VALUE] => 0.00
						)
					)
					[TOTALCHARGES] => Array(
						[CURRENCYCODE] => Array(
							[VALUE] => USD
						)
						[MONETARYVALUE] => Array(
							[VALUE] => 27.24
						)
					)
					[WEIGHT] => Array(
						[VALUE] => 23.0
					)
					[BILLINGWEIGHT] => Array(
						[UNITOFMEASUREMENT] => Array(
							[CODE] => Array(
								[VALUE] => LBS
							)
						)
						[WEIGHT] => Array(
							[VALUE] => 42.0
						)
					)
				)
			)
		)
	)	*/
	$accept = $tree['RATINGSERVICESELECTIONRESPONSE']['RESPONSE']['RESPONSESTATUSCODE']['VALUE'];
	if($accept == "1"){
		$rate = $tree['RATINGSERVICESELECTIONRESPONSE']['RATEDSHIPMENT']['RATEDPACKAGE']['TOTALCHARGES']['MONETARYVALUE']['VALUE'];
	} else {
		$rate = "-1";
	}
	return $rate;
}
?>