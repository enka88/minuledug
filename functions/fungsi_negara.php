<?php
if(!defined("CMSBalitbang")) {
	die("<h1>Permission Denied</h1>You don't have permission to access the this page.");
}
function negara($kode) {
switch ($kode) {
		case "ID":
			$negara="Indonesia";
			break;
		case "US":
			$negara="United States";
			break;
		case "AF":
			$negara="Afghanistan";
			break;
		case "AL":
			$negara="Albania";
			break;
		case "DZ":
			$negara="Algeria";
			break;
		case "AU":
			$negara="Australia";
			break;
		case "AT":
			$negara="Austria";
			break;
		case "CA":
			$negara="Canada";
			break;
		case "BH":
			$negara="Bahrain";
			break;
		case "BD":
			$negara="Bangladesh";
			break;
		case "BE":
			$negara="Belgium";
			break;
		case "BO":
			$negara="Bolivia";
			break;
		case "BR":
			$negara="Brazil";
			break;
		case "IO":
			$negara="British Indian Ocean Territory";
			break;
		case "BN":
			$negara="Brunei Darussalam";
			break;
		case "BG":
			$negara="Bulgaria";
			break;
		case "KH":
			$negara="Cambodia";
			break;
		case "CM":
			$negara="Cameroon";
			break;
		case "CL":
			$negara="Chile";
			break;
		case "CN":
			$negara="China";
			break;
		case "CO":
			$negara="Colombia";
			break;
		case "CG":
			$negara="Congo";
			break;
       case "CR":$negara="Costa Rica";break;
      case "HR":$negara="Croatia";break;
      
        case "CU":$negara="Cuba";break;
      case "CY":$negara="Cyprus";break;
      
        case "CZ":$negara="Czech Republic";break;
      case "DK":$negara="Denmark";break;
      case "TP":$negara="East Timor";break;
      case "EC":$negara="Ecuador";break;
      
        case "EG":$negara="Egypt";break;
      case "SV":$negara="El Salvador";break;
      case "EE":$negara="Estonia";break;
      case "ET":$negara="Ethiopia";break;
       
        case "FI":$negara="Finland";break;
      case "FR":$negara="France";break;
      case "GF":$negara="French Guiana";break;
      
        case "GE":$negara="Georgia";break;
      case "DE":$negara="Germany";break;
      
        case "GR":$negara="Greece";break;
      
        case "HK":$negara="Hong Kong";break;
      case "HU":$negara="Hungary";break;
      
        case "IS":$negara="Iceland";break;
      case "IN":$negara="India";break;
      
        case "ID":$negara="Indonesia";break;
      case "IR":$negara="Iran";break;
      
        case "IQ":$negara="Iraq";break;
      case "IE":$negara="Ireland";break;
      
        case "IL":$negara="Israel";break;
      case "IT":$negara="Italy";break;
      
        case "JM":$negara="Jamaica";break;
      case "JP":$negara="Japan";break;
      
        case "JO":$negara="Jordan";break;
      case "KZ":$negara="Kazakhstan";break;
      
        case "KE":$negara="Kenya";break;
      case "KI":$negara="Kiribati";break;
      
        case "KW":$negara="Kuwait";break;
      
        case "LA":$negara="Laos";break;
      case "LV":$negara="Latvia";break;
      
        case "LB":$negara="Lebanon";break;
      
        case "LR":$negara="Liberia";break;
      case "LY":$negara="Libya";break;
      case "LT":$negara="Lithuania";break;
      case "LU":$negara="Luxembourg";break;
      case "MK":$negara="Macedonia";break;
      case "MG":$negara="Madagascar";break;
      case "MW":$negara="Malawi";break;
      case "MY":$negara="Malaysia";break;
      case "MX":$negara="Mexico";break;
      
        case "FM":$negara="Micronesia";break;
      
        case "MC":$negara="Monaco";break;
      case "MN":$negara="Mongolia";break;
      case "MA":$negara="Morocco";break;
      case "MM":$negara="Myanmar";break;
      
        case "NP":$negara="Nepal";break;
      case "NL":$negara="Netherlands";break;
      case "NZ":$negara="New Zealand";break;
      case "NE":$negara="Niger";break;
      
        case "NG":$negara="Nigeria";break;
      case "KP":$negara="North Korea";break;
      
        case "NO":$negara="Norway";break;
      case "OM":$negara="Oman";break;
      
        case "PK":$negara="Pakistan";break;
       
        case "PS":$negara="Palestinian Territory";break;
      case "PA":$negara="Panama";break;
      case "PG":$negara="Papua New Guinea";break;
      
        case "PY":$negara="Paraguay";break;
      case "PE":$negara="Peru";break;
      
        case "PH":$negara="Philippines";break;
      
        case "PL":$negara="Poland";break;
      
        case "PT":$negara="Portugal";break;
      
        case "QA":$negara="Qatar";break;
      
        case "RO":$negara="Romania";break;
      case "RU":$negara="Russian Federation";break;
      case "RW":$negara="Rwanda";break;
      case "SH":$negara="Saint Helena";break;
       
        case "SA":$negara="Saudi Arabia";break;
      case "SN":$negara="Senegal";break;
     case "SG":$negara="Singapore";break;
      case "SK":$negara="Slovakia";break;
      case "SI":$negara="Slovenia";break;
       
        case "ZA":$negara="South Africa";break;
      case "KR":$negara="South Korea";break;
      case "ES":$negara="Spain";break;
      case "LK":$negara="Sri Lanka";break;
      case "SD":$negara="Sudan";break;
      case "SR":$negara="Suriname";break;
      case "SE":$negara="Sweden";break;
      case "CH":$negara="Switzerland";break;
      case "TW":$negara="Taiwan";break;
      
        case "TH":$negara="Thailand";break;
      case "TN":$negara="Tunisia";break;
      case "TR":$negara="Turkey";break;
      case "TM":$negara="Turkmenistan";break;
       case "UG":$negara="Uganda";break;
      
        case "UA":$negara="Ukraine";break;
      case "AE":$negara="United Arab Emirates";break;
      case "GB":$negara="United Kingdom";break;
      
        case "UY":$negara="Uruguay";break;
      case "UZ":$negara="Uzbekistan";break;
      case "VE":$negara="Venezuela";break;
      
        case "VN":$negara="Vietnam";break;
      case "WF":$negara="Wallis and Futuna Islands";break;
      case "YE":$negara="Yemen";break;
      
        case "YU":$negara="Yugoslavia";break;
      case "ZR":$negara="Zaire";break;
      
        case "ZM":$negara="Zambia";break;
      case "ZW":$negara="Zimbabwe";break;


		default:
			$negara="Indonesia";
			break;
		}

return $negara;
}


function datanegara2() {
$datanegara .="<select name='country' id='country'  >
      <option value='' selected></option>
	  <option value=ID>INDONESIA</option>
      <option value=US>UNITED STATES</option> 
      <option value=AF>Afghanistan</option>
      <option value=AL>Albania</option>
      <option value=DZ>Algeria</option>
      <option value=AR>Argentina</option>
      <option 
        value=AU>Australia</option>
      <option value=AT>Austria</option>
  	  <option value=CA>Canada</option>
      <option 
        value=BH>Bahrain</option>
      <option value=BD>Bangladesh</option>
      <option 
        value=BE>Belgium</option>
      <option value=BO>Bolivia</option>
      <option value=BR>Brazil</option>
      <option value=IO>British Indian Ocean Territory</option>
      <option 
        value=BN>Brunei Darussalam</option>
      <option value=BG>Bulgaria</option>
      <option value=KH>Cambodia</option>
      <option value=CM>Cameroon</option>
      <option value=CL>Chile</option>
      <option 
        value=CN>China</option>
      <option 
        value=CO>Colombia</option>
      <option 
        value=CG>Congo</option>
      <option 
        value=CR>Costa Rica</option>
      <option value=HR>Croatia</option>
      <option 
        value=CU>Cuba</option>
      <option value=CY>Cyprus</option>
      <option 
        value=CZ>Czech Republic</option>
      <option value=DK>Denmark</option>
      <option value=TP>East Timor</option>
      <option value=EC>Ecuador</option>
      <option 
        value=EG>Egypt</option>
      <option value=SV>El Salvador</option>
      <option value=EE>Estonia</option>
      <option value=ET>Ethiopia</option>
       <option 
        value=FI>Finland</option>
      <option value=FR>France</option>
      <option value=GF>French Guiana</option>
      <option 
        value=GE>Georgia</option>
      <option value=DE>Germany</option>
      <option 
        value=GR>Greece</option>
      <option 
        value=HK>Hong Kong</option>
      <option value=HU>Hungary</option>
      <option 
        value=IS>Iceland</option>
      <option value=IN>India</option>
      <option 
        value=ID>Indonesia</option>
      <option value=IR>Iran</option>
      <option 
        value=IQ>Iraq</option>
      <option value=IE>Ireland</option>
      <option 
        value=IL>Israel</option>
      <option value=IT>Italy</option>
      <option 
        value=JM>Jamaica</option>
      <option value=JP>Japan</option>
      <option 
        value=JO>Jordan</option>
      <option value=KZ>Kazakhstan</option>
      <option 
        value=KE>Kenya</option>
      <option value=KI>Kiribati</option>
      <option 
        value=KW>Kuwait</option>
      <option 
        value=LA>Laos</option>
      <option value=LV>Latvia</option>
      <option 
        value=LB>Lebanon</option>
      <option 
        value=LR>Liberia</option>
      <option value=LY>Libya</option>
      <option value=LT>Lithuania</option>
      <option value=LU>Luxembourg</option>
      <option value=MK>Macedonia</option>
      <option value=MG>Madagascar</option>
      <option value=MW>Malawi</option>
      <option value=MY>Malaysia</option>
      <option value=MX>Mexico</option>
      <option 
        value=FM>Micronesia</option>
      <option 
        value=MC>Monaco</option>
      <option value=MN>Mongolia</option>
      <option value=MA>Morocco</option>
      <option value=MM>Myanmar</option>
      <option 
        value=NP>Nepal</option>
      <option value=NL>Netherlands</option>
      <option value=NZ>New Zealand</option>
      <option value=NE>Niger</option>
      <option 
        value=NG>Nigeria</option>
      <option value=KP>North Korea</option>
      <option 
        value=NO>Norway</option>
      <option value=OM>Oman</option>
      <option 
        value=PK>Pakistan</option>
       <option 
        value=PS>Palestinian Territory</option>
      <option value=PA>Panama</option>
      <option value=PG>Papua New Guinea</option>
      <option 
        value=PY>Paraguay</option>
      <option value=PE>Peru</option>
      <option 
        value=PH>Philippines</option>
      <option 
        value=PL>Poland</option>
      <option 
        value=PT>Portugal</option>
      <option 
        value=QA>Qatar</option>
      <option 
        value=RO>Romania</option>
      <option value=RU>Russian Federation</option>
      <option value=RW>Rwanda</option>
      <option value=SH>Saint Helena</option>
       <option 
        value=SA>Saudi Arabia</option>
      <option value=SN>Senegal</option>
     <option value=SG>Singapore</option>
      <option value=SK>Slovakia</option>
      <option value=SI>Slovenia</option>
       <option 
        value=ZA>South Africa</option>
      <option value=KR>South Korea</option>
      <option value=ES>Spain</option>
      <option value=LK>Sri Lanka</option>
      <option value=SD>Sudan</option>
      <option value=SR>Suriname</option>
      <option value=SE>Sweden</option>
      <option value=CH>Switzerland</option>
      <option value=TW>Taiwan</option>
      <option 
        value=TH>Thailand</option>
      <option value=TN>Tunisia</option>
      <option value=TR>Turkey</option>
      <option value=TM>Turkmenistan</option>
       <option value=UG>Uganda</option>
      <option 
        value=UA>Ukraine</option>
      <option value=AE>United Arab Emirates</option>
      <option value=GB>United Kingdom</option>
      <option 
        value=UY>Uruguay</option>
      <option value=UZ>Uzbekistan</option>
      <option value=VE>Venezuela</option>
      <option 
        value=VN>Vietnam</option>
      <option value=WF>Wallis and Futuna Islands</option>
      <option value=YE>Yemen</option>
      <option 
        value=YU>Yugoslavia</option>
      <option value=ZR>Zaire</option>
      <option 
        value=ZM>Zambia</option>
      <option value=ZW>Zimbabwe</option>
    </select>";
return $datanegara;
}
?>