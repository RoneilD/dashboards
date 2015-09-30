<?PHP
/** Class::fleetDayHandler
* @author Justin Ward <justinw@manlinegroup.com>
* @copyright 2010 Manline Group (Pty) Ltd

* @requires This class requires access to the FileParser class. 

* @example First Example Start
* require_once(BASE.'basefunctions/baseapis/fleetDayHandler.php');
* $fleetdayobj    = new fleetDayHandler();
* $today    = date("d");
* $fleetscore           = $fleetdayobj->pullFleetDay($today);
* @example First Example End
**/
require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");

class fleetDayHandler {
	protected $_incomefleets = array(
		array('structure'=>array('Freight'), 'id'=>2, 'maxid'=>28, 'name'=>'Long Distance', 'budget'=>508752, 'budkms'=>44705, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>1, 'displayrasta'=>1, 'kms_limit'=>1800, 'open_time'=>10800),
		array('structure'=>array('Freight'), 'id'=>3, 'maxid'=>51, 'name'=>'LWT Fleet', 'budget'=>86894, 'budkms'=>7173, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>1, 'displayrasta'=>1, 'kms_limit'=>1800, 'open_time'=>28800),
		array('structure'=>array('Energy'), 'id'=>7, 'maxid'=>32, 'name'=>'Energy - Tankers', 'budget'=>44016, 'budkms'=>3313, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>1, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>8, 'maxid'=>53, 'name'=>'Energy - BHP Tankers', 'budget'=>117358, 'budkms'=>7213, 'pubhol'=>0, 'display'=>1, 'displayblackouts'=>1, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>9, 'maxid'=>86, 'name'=>'Energy - Total Sishen', 'budget'=>0, 'budkms'=>0, 'pubhol'=>0, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>10, 'maxid'=>42, 'name'=>'Energy - Buckman', 'budget'=>43112, 'budkms'=>2962, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>1, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>11, 'maxid'=>87, 'name'=>'Energy - Ecosse', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>0, 'displayblackouts'=>0, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>12, 'maxid'=>121, 'name'=>'Energy - NCP Chlorine', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>0, 'displayblackouts'=>0, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>13, 'maxid'=>126, 'name'=>'Energy - NCP Other', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>0, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>14, 'maxid'=>124, 'name'=>'Energy - NCP Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>0, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>15, 'maxid'=>123, 'name'=>'Energy - Easigas', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>0, 'displayblackouts'=>0, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Freight'), 'id'=>18, 'maxid'=>73, 'name'=>'Africa - Copperbelt Fleet', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>0, 'displayblackouts'=>0, 'displayrasta'=>1, 'kms_limit'=>2600, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>22, 'maxid'=>82, 'name'=>'Wilmar Bulk Fleet', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Freight'), 'id'=>26, 'maxid'=>83, 'name'=>'Ashton Fleet', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>1, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>33, 'maxid'=>33, 'name'=>'Energy (Own)', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Freight'), 'id'=>35, 'maxid'=>77, 'name'=>'Freight Africa', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>38, 'maxid'=>487, 'name'=>'Illovo - Germiston CCC', 'trucks'=>array('D616', 'D617', 'D618'), 'customer'=>array('Coca Cola Canners'), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>39, 'maxid'=>487, 'name'=>'Illovo - Germiston F and V', 'trucks'=>array('444069','444070','444071','444072','444073','D550', 'D577'), 'customer'=>array('Illovo Refined GBD F and V'), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>40, 'maxid'=>113, 'name'=>'Meadow Feeds - PMB', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>42, 'maxid'=>168, 'name'=>'Toyota - X Dock', 'trucks'=>array('525199','525200','525201','525250','525251','525252','545249','545250','545251','545252','545253','545254','Hire1','Hire2','Hire3','Hire4'), 'customer'=>array('Toyota X-Dock F & V', 'Toyota X-Dock'), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Freight'), 'id'=>43, 'maxid'=>101, 'name'=>'Toyota Tsusho', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>44, 'maxid'=>106, 'name'=>'Toyota - Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>45, 'maxid'=>99, 'name'=>'Illovo - Avoca', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>46, 'maxid'=>114, 'name'=>'PPC - Hercules', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>47, 'maxid'=>115, 'name'=>'PPC - Heriotdale', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>48, 'maxid'=>108, 'name'=>'Meadow Feeds - Paarl', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>49, 'maxid'=>116, 'name'=>'PPC - Slurry', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>50, 'maxid'=>118, 'name'=>'Meadow Feeds - Delmas', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>51, 'maxid'=>100, 'name'=>'Corobrick - Avoca', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>52, 'maxid'=>112, 'name'=>'Meadow Feeds - P.E', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>53, 'maxid'=>109, 'name'=>'PPC - Kraaifontein', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>54, 'maxid'=>110, 'name'=>'PPC - George', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>55, 'maxid'=>111, 'name'=>'PPC - P.E', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>56, 'maxid'=>160, 'name'=>'RE - Cape', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>57, 'maxid'=>159, 'name'=>'RE - Denver', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>58, 'maxid'=>158, 'name'=>'RE - KZN', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>59, 'maxid'=>98, 'name'=>'RE- Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>60, 'maxid'=>117, 'name'=>'PPC - Dwaalboom', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>2000, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>61, 'maxid'=>131, 'name'=>'PPC - Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>62, 'maxid'=>150, 'name'=>'Manline Mega', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Dedicated'), 'id'=>63, 'maxid'=>91, 'name'=>'Anglo - Dedicated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>64, 'maxid'=>92, 'name'=>'Anglo - Sub Contractor', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>65, 'maxid'=>93, 'name'=>'Anglo Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>66, 'maxid'=>119, 'name'=>'Meadow Feeds - Randfontein', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>67, 'maxid'=>169, 'name'=>'Meadow Feeds - Standerton', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>68, 'maxid'=>132, 'name'=>'Meadow Feeds - Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>69, 'maxid'=>151, 'name'=>'Premier Durban', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>71, 'maxid'=>153, 'name'=>'Premier Empangeni', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>72, 'maxid'=>155, 'name'=>'Premier Newcastle', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>74, 'maxid'=>163, 'name'=>'Premier Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>77, 'maxid'=>328, 'name'=>'T24 - Merensky', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>78, 'maxid'=>334, 'name'=>'T24 - Mondi', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>79, 'maxid'=>332, 'name'=>'T24 - Sappi KZN', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>80, 'maxid'=>165, 'name'=>'Anglo - Mokopane Fleet', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>81, 'maxid'=>166, 'name'=>'Anglo - Rustenburg', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>82, 'maxid'=>168, 'name'=>'Commercial', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>83, 'maxid'=>206, 'name'=>'Manline Mega - 75 Tons', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>84, 'maxid'=>207, 'name'=>'Manline Mega - 55 Tons', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>85, 'maxid'=>208, 'name'=>'Manline Mega - 80/90/100 Tons', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>86, 'maxid'=>209, 'name'=>'Manline Mega - 45 Tons', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>87, 'maxid'=>210, 'name'=>'Manline Mega - 40 Tons', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>88, 'maxid'=>212, 'name'=>'Manline Mega - Standards', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>89, 'maxid'=>211, 'name'=>'Manline Mega - 14 Tons', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Dedicated'), 'id'=>90, 'maxid'=>222, 'name'=>'Construction', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>92, 'maxid'=>170, 'name'=>'Idwala', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>93, 'maxid'=>225, 'name'=>'Manline Mega - Fleet 1', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>94, 'maxid'=>224, 'name'=>'Manline Mega - Fleet 2', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>95, 'maxid'=>223, 'name'=>'Manline Mega - Fleet 3', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Specialised'), 'id'=>96, 'maxid'=>270, 'name'=>'Manline Mega - Own', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>43200),
		array('structure'=>array('Dedicated'), 'id'=>97, 'maxid'=>96, 'name'=>'Illovo Refined-Noodesburg', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>98, 'maxid'=>97, 'name'=>'Illovo - Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>99, 'maxid'=>275, 'name'=>'FMCG Commercial JHB', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>100, 'maxid'=>276, 'name'=>'Environmental', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>101, 'maxid'=>277, 'name'=>'Pen Bev', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>102, 'maxid'=>282, 'name'=>'Agriculture', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>103, 'maxid'=>283, 'name'=>'Mining', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>106, 'maxid'=>338, 'name'=>'Timber24', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
		array('structure'=>array('Dedicated'), 'id'=>119, 'maxid'=>301, 'name'=>'Dedicated (own)', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Freight'), 'id'=>109, 'maxid'=>144, 'name'=>'Freight Subcontractors', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Energy'), 'id'=>111, 'maxid'=>296, 'name'=>'Energy Subcontractors', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
		array('structure'=>array('Specialised'), 'id'=>107, 'maxid'=>397, 'name'=>'Specialised', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Specialised'), 'id'=>110, 'maxid'=>398, 'name'=>'Specialised (Own)', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>113, 'maxid'=>297, 'name'=>'Dedicated Subcontractors', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Specialised'), 'id'=>114, 'maxid'=>298, 'name'=>'Specialised Subcontractors', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Energy'), 'id'=>120, 'maxid'=>302, 'name'=>'Energy Fuel and Gas', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Energy'), 'id'=>121, 'maxid'=>303, 'name'=>'Energy Chemicals', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>123, 'maxid'=>281, 'name'=>'Tongaat Hulletts', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>124, 'maxid'=>308, 'name'=>'Mining - Adhoc', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Specialised'), 'id'=>125, 'maxid'=>339, 'name'=>'T24 - Revenue - Hfts AdHoc', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>126, 'maxid'=>309, 'name'=>'RE - Rental', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>127, 'maxid'=>280, 'name'=>'Warehousing Dedicated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Freight'), 'id'=>128, 'maxid'=>234, 'name'=>'Warehousing Freight', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>129, 'maxid'=>300, 'name'=>'Festive', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Energy'), 'id'=>131, 'maxid'=>271, 'name'=>'Energy - NCP Cato', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Specialised'), 'id'=>134, 'maxid'=>340, 'name'=>'T24 - Howick Adhoc', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
        array('structure'=>array('Freight'), 'id'=>136, 'maxid'=>221, 'name'=>'Freight - FMCG', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Freight'), 'id'=>137, 'maxid'=>122, 'name'=>'Africa - General Freight', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>2600, 'open_time'=>10800),
        array('structure'=>array('Freight'), 'id'=>138, 'maxid'=>413, 'name'=>'Freight - Volumax Fleet', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1800, 'open_time'=>28800),
        array('structure'=>array('Freight'), 'id'=>139, 'maxid'=>420, 'name'=>'Africa - Zambia Fleet', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>2600, 'open_time'=>10800),
        array('structure'=>array('Specialised'), 'id'=>141, 'maxid'=>424, 'name'=>'T24 - Revenue Cane', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
        array('structure'=>array('Specialised'), 'id'=>142, 'maxid'=>425, 'name'=>'T24 - Revenue MPU Adhoc', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>600, 'open_time'=>10800),
        array('structure'=>array('Specialised'), 'id'=>143, 'maxid'=>405, 'name'=>'Kumkani', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>144,'maxid'=>449, 'name'=>'Glencore', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Freight'), 'id'=>145, 'maxid'=>414, 'name'=>'LWT Consolidated', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1800, 'open_time'=>28800),
        array('structure'=>array('Energy'), 'id'=>146, 'maxid'=>482, 'name'=>'Energy - TSA bridging', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>147, 'maxid'=>481, 'name'=>'Anglo - Polokwane', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>148, 'maxid'=>481, 'name'=>'Anglo - PBS', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>149, 'maxid'=>486, 'name'=>'Goldi-Standerton (Catching)', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>150, 'maxid'=>488, 'name'=>'Goldi-Standerton (Hatchery)', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>151, 'maxid'=>487, 'name'=>'Illovo GBD', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        array('structure'=>array('Dedicated'), 'id'=>152, 'maxid'=>483, 'name'=>'Sime Darby Hudson & Knight', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        
        //: Fleets that are rolled up need to go right to the end
        //: Freight SA (own)
        array('structure'=>array('Freight'), 'id'=>34, "maxid"=>76, "name"=>"Freight SA (Own)", 'fleets'=>array(
        	array(2, 'Long Distance'),array(3, 'LWT Fleet'),array(138, 'Freight - Volumax Fleet'),array(26, 'Ashton Fleet'),array(128, 'Warehousing Freight')
        	), "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: Freight SA
        array('structure'=>array('Freight'), "id"=>122, 'maxid'=>304, "name"=>"Freight SA", 'fleets'=>array(
        	array(2, 'Long Distance'),array(3, 'LWT Fleet'),array(138, 'Freight - Volumax Fleet'),array(26, 'Ashton Fleet'),array(128, 'Warehousing Freight'),array(109, 'Freight Subcontractors')
        	), "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: Freight (own)
		array('structure'=>array('Freight'), 'id'=>118, 'maxid'=>305, 'name'=>'Freight (Own)', 'fleets'=>array(
			array(139,'Africa - Zambia Fleet'),array(128,'Warehousing Freight'),array(138,'Freight - Volumax Fleet'),array(137,'Africa - General Freight'),array(2,'Long Distance'),array(3,'LWT Fleet'),array(26,'Ashton Fleet'),array(18,'Africa - Copperbelt Fleet')
			), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: Freight
        array('structure'=>array('Freight'), 'id'=>117, 'maxid'=>306, 'name'=>'Freight', 'fleets'=>array(
        	array(109,'Freight Subcontractors'),array(139,'Africa - Zambia Fleet'),array(128,'Warehousing Freight'),array(138,'Freight - Volumax Fleet'),array(137,'Africa - General Freight'),array(2,'Long Distance'),array(3,'LWT Fleet'),array(26,'Ashton Fleet'),array(18,'Africa - Copperbelt Fleet')
        	), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: Energy
        array('structure'=>array('Energy'), "id"=>116, 'maxid'=>299, "name"=>"Energy", 'fleets'=>array(
        	array(120, 'Energy Fuel and Gas'), array(121, 'Energy Chemicals')
        	), "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: Dedicated
        array('structure'=>array('Dedicated'), 'id'=>104, 'maxid'=>284, 'name'=>'Dedicated', 'fleets'=>array(
        	array(102, 'Agriculture'),array(90, 'Construction'),array(100, 'Environmental'),array(82, 'FMCG'),array(103, 'Mining')
        	), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: BWT
        array('structure'=>array('Barloworld Transport'), 'id'=>108, "structure"=>array('BWT'), 'maxid'=>9002, 'name'=>'BWT Group', 'fleets'=>array(
			array(106, 'Timber24'), array(62, 'Manline Mega'), array(104, 'Dedicated'), array(33, 'Energy (Own)'), array(34, 'Freight SA (Own)'), array(35, 'Freight Africa'), array(109, 'Freight Subcontractors'), array(111, 'Energy Subcontractors')
            ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: Bwt (Own)
        array('structure'=>array('Barloworld Transport'), 'id'=>112, "structure"=>array('BWT'), 'maxid'=>9003, 'name'=>'BWT Group (Own)', 'fleets'=>array(
        	array(106, 'Timber24'), array(96, 'Manline Mega - Own'), array(119, 'Dedicated (own)'), array(33, 'Energy (own)'), array(34, 'Freight SA (Own)'), array(35, 'Freight Africa')
            ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        //: Subbies Consolidated
        array('structure'=>array('Barloworld Transport'), 'id'=>115, "structure"=>array('Subcontractors', 'Freight SA'), 'maxid'=>9003, 'name'=>'Subcontractors Consolidated', 'fleets'=>array(
        	array(109, 'Freight Subcontractors'),array(111, 'Energy Subcontractors'),array(113, 'Dedicated Subcontractors'),array(114, 'Specialised Subcontractors')
            ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800),
        /* 
        array('structure'=>array(), 'id'=>, 'maxid'=>, 'name'=>'', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0, 'kms_limit'=>1500, 'open_time'=>10800), 
        */
        );
    protected $_apiurl      = "https://login.max.bwtsgroup.com/api_request/Report/export?";
    // protected $_apiurl   = "http://max.mobilize.biz/api_request/Report/export?";
    protected $_day               = 0;
    protected $_date        = 0;
    protected $_fileParser;
    
    // Getters, or functions which return protected variables or details from them {
    public function getIncomeFleets() {
    	return $this->_incomefleets;
    }
    
    public function getFleetId($index) {
    	return $this->_incomefleets[$index]["id"];
    }
    
    /** fleetDayHandler::getFleetById($fleetid)
    * @param mixed $fleetid which fleet are we looking for
    * @return array fleet data on success false otherwise
    */ 
    public function getFleetById($fleetid) {
    	foreach ($this->_incomefleets as $val) {
    		if ($val["id"] == $fleetid) {
    			return $val;
    		}
    	}
    	return FALSE;
    }
    // }
    
    // Standard day functions {
    public function pullFleetDay($day, $range=0) {
    	$this->_day       = $day;
    	$this->_date  = mktime(0, 0, 0, date("m"), $day, date("Y"));
    	
    	// Create date strings for query {
    	$startmonth         = date("m");
    	$startyear          = date("Y");
    	$startday                 = date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
    	
    	$startstring  = $startyear."-".$startmonth."-".$startday;
    	//$startstring      = $startyear."-".$startmonth."-".$startday." 00:00";
    	
    	$stopdate           = mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
    	$stopday            = date("d", $stopdate);
    	$stopmonth    = date("m", $stopdate);
    	$stopyear           = date("Y", $stopdate);
    	
    	$stopstring   = $stopyear."-".$stopmonth."-".$stopday;
    	//$stopstring = $startyear."-".$startmonth."-".$startday." 23:59";
    	
    	print($startstring." to ".$stopstring.'<br />'.PHP_EOL);
    	// }
    	
    	// Go through each Income Fleet, checking various details and getting the trips for the day {
    	foreach ($this->_incomefleets as $incfleetkey=>$incfleetval)
    		if (array_key_exists('fleets', $incfleetval))
    		{
    			$fleetscore[$incfleetval["id"]]["fleetid"] = $incfleetval["id"];
    			$fleetscore[$incfleetval["id"]]["income"] = (float)0;
    			$fleetscore[$incfleetval["id"]]["kms"] = (float)0;
    			$fleetscore[$incfleetval["id"]]["subbie_income"] = (float)0;
    			$fleetscore[$incfleetval["id"]]["subbie_kms"] = (float)0;
    			$fleetscore[$incfleetval["id"]]["day"] = $this->_day;
    			$fleetscore[$incfleetval["id"]]["date"] = $this->_date;
    			$fleetscore[$incfleetval["id"]]["updated"] = date("U");
    			foreach ($incfleetval['fleets'] as $key=>$val)
    				// print_r($val);
    			$record = sqlPull(array(
    				"onerow"=>TRUE,
    				"table"=>"fleet_scores",
    				"where"=>"`fleetid`=".$val[0]." AND `date`=".$this->_date
    				));
    			if ($record)
    			{
    				$fleetscore[$incfleetval["id"]]["income"] += $record['income'];
    				$fleetscore[$incfleetval["id"]]["kms"] += $record['kms'];
    				$fleetscore[$incfleetval["id"]]["subbie_income"] += $record['subbie_income'];
    				$fleetscore[$incfleetval["id"]]["subbie_kms"] += $record['subbie_kms'];
    			}
    		}
    		else
    		{
    			$dayincome = 0;
    			$daykms = 0;
    			$blackoutcount = 0;
    			if (array_key_exists("t24", $incfleetval))
    			{
    				continue;
    			}
    			
    			// Pull the day's trips for this fleet {
    			// Import the trip data {
    			//$tripurl = "http://max.mobilize.biz/m4/2/api_request/Report/export?report=84&responseFormat=csv&Start_Date=2011-02-11&Stop_Date=2011-02-12&Fleet=29"; 
    			$tripurl = $this->_apiurl."report=84&responseFormat=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$incfleetval["maxid"];
    			print($tripurl.'<br />'.PHP_EOL);
    			$tripdata = $this->getDataFromFile($tripurl, "income.".$incfleetval["id"].".csv");
    			
    			if ($tripdata === false) {
    				syslog(LOG_INFO, "Line 305: No data returned from fleetDayHandler::getDataFromFile()");
    				continue;
    			}
    			// }
    			
    			$dayincome = (float)0.00;
    			$daykms = (int)0;
    			$subbie_income = (float)0.00;
    			$subbie_kms = (int)0;
    			foreach ($tripdata as $tripkey=>$tripval) {
    				if (array_key_exists('customer', $incfleetval))
    				{
    					if (array_key_exists('Customer', $tripval))
    					{
    						if (in_array($tripval['Customer'], $incfleetval['customer']) === FALSE)
    						{
    							continue;
    						}
    					}
    				}
    				$triptime   = "";
    				if(($tripval["Loading Arrival"] == "(none)") || ($tripval["Loading Arrival"] == NULL)) {
    					if (($tripval["Loading ETA"] == "(none)") || ($tripval["Loading ETA"] == NULL))
    					{
    						$triptime = $tripval["Loading Started"];
    					}
    					else
    					{
    						$triptime   = $tripval["Loading ETA"];
    					}
    				} else {
    					$triptime   = $tripval["Loading Arrival"];
    				}
    				
    				$cutofftime = $stopyear."-".$stopmonth."-".$stopday." 00:00:00";
    				//$cutofftime     = $startyear."-".$startmonth."-".$startday." 22:00:00";
    				
    				if($triptime != $cutofftime) {
    					$dayincome  += array_key_exists("Tripleg Income", $tripval) ? str_replace(",", "", $tripval["Tripleg Income"]) : 0;
    					//$daycontrib     += str_replace(",", "", $tripval["Tripleg Contrib"]);
    					# $daykms               += array_key_exists("Total Kms", $tripval) ? $tripval["Total Kms"] : 0;
    					if ($tripval["Total Kms"] > 7000)
    					{
    						$daykms     += array_key_exists("Expected Total Kms", $tripval) ? $tripval["Expected Total Kms"] : 0;
    					}
    					else
    					{
    						$daykms     += array_key_exists("Total Kms", $tripval) ? $tripval["Total Kms"] : 0;
    					}
    					if (array_key_exists('Subcontractor', $tripval) && $tripval['Subcontractor'] && ($tripval['Subcontractor'] !== '(none)'))
    					{
    						$subbie_income    += array_key_exists("Tripleg Income", $tripval) ? str_replace(",", "", $tripval["Tripleg Income"]) : 0;
    						$subbie_kms += array_key_exists("Total Kms", $tripval) ? $tripval["Total Kms"] : 0;
    					}
    				}
    			}
    			
    			$fleetscore[$incfleetval["id"]]["fleetid"] = $incfleetval["id"];
    			$fleetscore[$incfleetval["id"]]["income"] = $dayincome;
    			$fleetscore[$incfleetval["id"]]["kms"] = $daykms;
    			$fleetscore[$incfleetval["id"]]["subbie_income"] = $subbie_income;
    			$fleetscore[$incfleetval["id"]]["subbie_kms"] = $subbie_kms;
    			$fleetscore[$incfleetval["id"]]["day"] = $this->_day;
    			$fleetscore[$incfleetval["id"]]["date"] = $this->_date;
    			$fleetscore[$incfleetval["id"]]["updated"] = date("U");
    			// }
    		}
    		// }
    		
    		return $fleetscore;
    }
    
    public function findBackDay($today) {
    	$file = (string)'max';
    	$backday = $today - 2; // This is where to start searching from.  Currently, we pull the most recent 2 days every 5 minutes, so back days must start before that.
    	print("back day: ".$backday);
    	$fptr = fopen(dirname(realpath(__FILE__))."/".$file, 'c+');
    	$contents = fread($fptr, (filesize(dirname(realpath(__FILE__))."/".$file) > 0 ? filesize(dirname(realpath(__FILE__))."/".$file) : 1));
    	if (!$contents)
    	{
    		print('File doesn\'t have contents. Creating and updating it now'.PHP_EOL);
    		fwrite($fptr, $backday-1);
    		fclose($fptr);
    		return $backday;
    	}
    	else
    	{
    		//: We now need to start decrementing the file
    		fclose($fptr);
    		$fptr = fopen(dirname(realpath(__FILE__))."/".$file, 'w');
    		if ($contents == 1)
    		{
    			fwrite($fptr, $backday);
    		}
    		else
    		{
    			fwrite($fptr, (int)$contents-1);
    		}
    		fclose($fptr);
    		return $contents;
    	}
    }
    // }
    
    // Contrib day functions {
    public function pullFleetDayWithContrib($day, $range=0) {
    	$this->_day       = $day;
    	$this->_date  = mktime(0, 0, 0, date("m"), $day, date("Y"));
    	
    	// Create date strings for query {
    	$startmonth         = date("m");
    	$startyear          = date("Y");
    	$startday                 = date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
    	
    	$startstring  = $startyear."-".$startmonth."-".$startday;
    	//$startstring      = $startyear."-".$startmonth."-".$startday." 00:00";
    	
    	$stopdate           = mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
    	$stopday            = date("d", $stopdate);
    	$stopmonth    = date("m", $stopdate);
    	$stopyear           = date("Y", $stopdate);
    	
    	$stopstring   = $stopyear."-".$stopmonth."-".$stopday;
    	//$stopstring = $startyear."-".$startmonth."-".$startday." 23:59";
    	
    	print($startstring." to ".$stopstring.PHP_EOL);
    	// }
    	
    	// Go through each Income Fleet, checking various details and getting the trips for the day {
    	$key = array_keys($this->_incomefleets);
    	$size = sizeOf($key);
    	// foreach ($this->_incomefleets as $incfleetkey=>$incfleetval)
    	for ($i=0; $i<$size; $i++)
    	{
    		$incfleetkey = $key[$i];
    		if (array_key_exists($key[$i], $this->_incomefleets) === FALSE)
    		{
    			syslog(LOG_INFO, "Trying to get contribution for a non-exsistent array key: ".$incfleetkey);
    			continue;
    		}
    		$incfleetval = $this->_incomefleets[$key[$i]];
    		if (array_key_exists('fleets', $incfleetval))
    		{
    			$fleetscore[$incfleetval["id"]]["fleetid"] = $incfleetval["id"];
    			$fleetscore[$incfleetval["id"]]["contrib"] = (float)0;
    			$fleetscore[$incfleetval["id"]]["day"] = $this->_day;
    			$fleetscore[$incfleetval["id"]]["date"] = $this->_date;
    			$fleetscore[$incfleetval["id"]]["contribupdated"]     = date("U");
    			$fleet_key = array_keys($incfleetval['fleets']);
    			$fleet_size = sizeOf($key);
    			//foreach ($incfleetval['fleets'] as $key=>$val)
    			for ($j=0; $j<$fleet_size; $j++)
    			{
    				if (array_key_exists($j, $fleet_key) === FALSE)
    				{
    					syslog(LOG_INFO, "Line 469: Key doesn't exist in array: ".$j);
    					continue;
    				}
    				if (array_key_exists($j, $fleet_key) === TRUE)
    				{
    					if (array_key_exists($fleet_key[$j], $incfleetval['fleets']) === FALSE)
    					{
    						syslog(LOG_INFO, "Line 476: Trying to get income for a non-exsistent array key: ".$fleet_key[$j]);
    						continue;
    					}
    				}
    				$val = $incfleetval['fleets'][$fleet_key[$j]];
    				//print_r($val);
    				$record = sqlPull(array(
    					"onerow"=>TRUE,
    					"table"=>"fleet_scores",
    					"where"=>"`fleetid`=".$val[0]." AND `date`=".$this->_date
    					));
    				if ($record)
    				{
    					$fleetscore[$incfleetval["id"]]["contrib"] += $record['contrib'];
    				}
    			}
    		}
    		else
    		{
    			//: There is a separate process for getting budget and income for T24 fleets for now
    			if (array_key_exists("t24", $incfleetval))
    			{
    				continue;
    			}
    			$daycontrib = 0;
    			$daykms = 0;
    			
    			// Pull the day's trips for this fleet {
    			// Import the trip data {
    			//$tripurl = "http://max.mobilize.biz/m4/2/api_request/Report/export?report=84&responseFormat=csv&Start_Date=2011-02-11&Stop_Date=2011-02-12&Fleet=29"; 
    			$tripurl = $this->_apiurl."report=138&responseFormat=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$incfleetval["maxid"];
    			print($tripurl.PHP_EOL);
    			$tripdata = $this->getDataFromFile($tripurl, "contrib.".$incfleetval["id"].".csv");
    			
    			if ($tripdata === false) {
    				syslog(LOG_INFO, "Line 498: No data returned from fleetDayHandler::getDataFromFile()");
    				continue;
    			}
    			// }
    			
    			foreach ($tripdata as $tripkey=>$tripval) {
    				$triptime   = "";
    				if((array_key_exists("Loading Arrival", $tripval)) && (isset($tripval["Loading Arrival"])) && ($tripval["Loading Arrival"] == "(none)") || ($tripval["Loading Arrival"] == null)) {
    					$triptime   = isset($tripval["Loading ETA"]) ? $tripval["Loading ETA"] : 2/24;
    				} else {
    					$triptime   = isset($tripval["Loading Arrival"]) ? $tripval["Loading Arrival"] : 2/24;
    				}
    				
    				$cutofftime = $stopyear."-".$stopmonth."-".$stopday." 00:00:00";
    				//$cutofftime     = $startyear."-".$startmonth."-".$startday." 22:00:00";
    				
    				if($triptime != $cutofftime) {
    					if (isset($tripval["Tripleg Contrib"])) {
    						$daycontrib += str_replace(",", "", $tripval["Tripleg Contrib"]);
    					}
    					if (isset($tripval["Total Kms"])) {
    						$daykms                 += $tripval["Total Kms"];
    					}
    				}
    			}
    			
    			$fleetscore[$incfleetval["id"]]["fleetid"]                        = $incfleetval["id"];
    			$fleetscore[$incfleetval["id"]]["contrib"]                        = $daycontrib;
    			$fleetscore[$incfleetval["id"]]["day"]                                  = $this->_day;
    			$fleetscore[$incfleetval["id"]]["date"]                                 = $this->_date;
    			$fleetscore[$incfleetval["id"]]["contribupdated"]     = date("U");
    			// }
    		}
    	}
    	// }
    	
    	return $fleetscore;
    }
    
    public function findContribBackDay($today) {
    	$file = (string)'contrib';
    	$backday = $today - 2; // This is where to start searching from.  Currently, we pull the most recent 2 days every 5 minutes, so back days must start before that.
    	print("back day: ".$backday);
    	$fptr = fopen(dirname(realpath(__FILE__))."/".$file, 'c+');
    	$contents = fread($fptr, (filesize(dirname(realpath(__FILE__))."/".$file) > 0 ? filesize(dirname(realpath(__FILE__))."/".$file) : 1));
    	if (!$contents)
    	{
    		print('File doesn\'t have contents. Creating and updating it now'.PHP_EOL);
    		fwrite($fptr, $backday-1);
    		fclose($fptr);
    		return $backday;
    	}
    	else
    	{
    		//: We now need to start decrementing the file
    		fclose($fptr);
    		$fptr = fopen(dirname(realpath(__FILE__))."/".$file, 'w');
    		if ($contents == 1)
    		{
    			fwrite($fptr, $backday);
    		}
    		else
    		{
    			fwrite($fptr, (int)$contents-1);
    		}
    		fclose($fptr);
    		return $contents;
    	}
    }
    // }
    
    //: Order functions
    /** fleetdayHandler::importOrders()
    * Import order data so that we can get an accurate read on the number of blackouts
    * @author Feighen Oosterbroek
    * @author feighen@manlinegroup.com
    * @return FALSE on failure NULL otherwise
    */
    public function importOrders() {
    	$key = array_keys($this->_incomefleets);
    	$size = sizeOf($key);
    	// foreach ($this->_incomefleets as $incfleetkey=>$incfleetval)
    	for ($i=0; $i<$size; $i++)
    	{
    		$incfleetkey = $key[$i];
    		if (array_key_exists($key[$i], $this->_incomefleets) === FALSE)
    		{
    			syslog(LOG_INFO, "Trying to get orders for a non-exsistent array key: ".$incfleetkey);
    			continue;
    		}
    		$incfleetval = $this->_incomefleets[$key[$i]];
    		if (array_key_exists("t24", $incfleetval))
    		{
    			continue;
    		}
    		//: Preparation
    		$blackoutcount = (float)0;
    		//: End
    		if (array_key_exists('fleets', $incfleetval))
    		{
    			$fleetscore["blackouts"] = $blackoutcount;
    			$fleet_key = array_keys($incfleetval['fleets']);
    			$fleet_size = sizeOf($key);
    			//foreach ($incfleetval['fleets'] as $key=>$val)
    			for ($j=0; $j<$fleet_size; $j++)
    			{
    				if (array_key_exists($j, $fleet_key) === FALSE)
    				{
    					syslog(LOG_INFO, "Line 630: Key doesn't exist in array: ".$j);
    					continue;
    				}
    				if (array_key_exists($j, $fleet_key) === TRUE)
    				{
    					if (array_key_exists($fleet_key[$j], $incfleetval['fleets']) === FALSE)
    					{
    						syslog(LOG_INFO, "Line 637: Trying to get income for a non-exsistent array key: ".$fleet_key[$j]);
    						continue;
    					}
    				}
    				$val = $incfleetval['fleets'][$fleet_key[$j]];
    				//print_r($val);
    				$record = sqlPull(array(
    					"onerow"=>TRUE,
    					"table"=>"fleet_scores",
    					"where"=>"`fleetid`=".$val[0]." AND `date`=".$this->_date
    					));
    				if ($record)
    				{
    					$fleetscore['blackouts'] += $record['blackouts'];
    				}
    			}
    		}
    		else
    		{
    			//: There is a separate process for getting budget and income for T24 fleets for now
    			if (array_key_exists("t24", $incfleetval))
    			{
    				continue;
    			}
    			//: Confirm the budgeted blackouts
    			$budgeturl = $this->_apiurl."report=85&responseFormat=csv&Start_Date=".date("Y-m-d")."&Stop_Date=".date("Y-m-d", strtotime("+1 day"))."&Fleet=".$incfleetval["maxid"];
    			// print("budgeturl: ".$budgeturl.PHP_EOL);
    			$budgetdata = $this->getDataFromFile($budgeturl, "budget.".$incfleetval["id"].".csv");
    			if ($budgetdata === false) {
    				syslog(LOG_INFO, "Line 642: No data returned from fleetDayHandler::getDataFromFile()");
    				continue;
    			}
    			//: Collate
    			foreach ($budgetdata as $budgetkey=>$budgetval) {
    				$truckbudget = isset($budgetval["Income"]) ? str_replace(",", "", $budgetval["Income"]) : "";
    				$truckbudget = str_replace("R", "", $truckbudget);
    				// Calculate the number of trucks per fleet that have a budget and no trip
    				if(isset($budgetval["Blackout Status"]) && (($budgetval["Blackout Status"] == "1") || ($budgetval["Blackout Status"] == "Yes"))) {
    					if($truckbudget > 0) {
    						$blackoutcount++;
    					}
    				}
    			}
    			// print("blackoutcount: ".$blackoutcount.PHP_EOL);
    			//: End
    			$ordersurl = $this->_apiurl."report=98&responseFormat=csv&Start_Date=".date("Y-m-d")."&Stop_Date=".date("Y-m-d", strtotime("+1 day"));
    			// print("ordersurl: ".$ordersurl.PHP_EOL);
    			$fileParser = new FileParser($ordersurl);
    			$fileParser->setCurlFile("orders".$incfleetval["id"].".csv");
    			$ordersdata = $fileParser->parseFile();
    			if ($ordersdata === false) {
    				print("<pre style='font-family:verdana;font-size:13'>");
    				print_r($fileParser->getErrors());
    				print("</pre>");
    				return;
    				print("<pre style='font-family:verdana;font-size:13'>errors");
    				print_r($fileParser->getErrors());
    				print("</pre>");
    				print("<br>");
    			}
    			foreach ($ordersdata as $orderkey=>$orderval) {
    				if ($incfleetval["id"] == 29) {
    					$blackoutcount--;
    				} else {
    					if ($orderval["Fleetid"] == $incfleetval["id"]) {
    						$blackoutcount--;
    					}
    				}
    			}
    			// print("blackoutcount: ".$blackoutcount.PHP_EOL);
    			//: Insert or update
    			$fleetscore = (array)array();
    			$fleetscore["blackouts"] = $blackoutcount;
    		}
    		//: Insert or update
    		$fleetscore = (array)array();
    		$fleetscore["blackouts"] = $blackoutcount;
    		//: check to see if this data needs to be updated or if it can just be inserted
    		$record = sqlPull(array(
    			"onerow"=>TRUE,
    			"table"=>"fleet_scores",
    			"where"=>"`fleetid`=".$incfleetval["id"]." AND `date`=".mktime(0,0,0,date("m"),date("d"),date("Y"))
    			));
    		if (isset($record) && $record) { //: Update
    			sqlCommit(array(
    				"table"=>"fleet_scores",
    				"where"=>"id=".$record["id"],
    				"fields"=>$fleetscore
    				));
    		} else { //: Insert
    			sqlCreate(array(
    				"table"=>"fleet_scores",
    				"fields"=>$fleetscore
    				));
    		}
    	}
    }
    //: End
    
    //: Budget functions
    /** fleetdayHandler::importBudget()
    * Import this months budget data
    * @author Feighen Oosterbroek
    * @author feighen@manlinegroup.com
    * @return FALSE on failure NULL otherwise
    */
    public function importBudget($fleet = NULL) {
    	for ($i=1;$i<=date("t");$i++) {
    		$key = array_keys($this->_incomefleets);
    		$size = sizeOf($key);
    		// foreach ($this->_incomefleets as $incfleetkey=>$incfleetval)
    		for ($j=0; $j<$size; $j++)
    		{
    			$incfleetkey = $key[$j];
    			if (array_key_exists($key[$j], $this->_incomefleets) === FALSE)
    			{
    				syslog(LOG_INFO, "Trying to get budget for a non-exsistent array key: ".$incfleetkey);
    				continue;
    			}
    			$incfleetval = $this->_incomefleets[$key[$j]];
    			if (isset($fleet) && $fleet)
    			{
    				if ($fleet != $incfleetval["id"])
    				{
    					continue;
    				}
    			}
    			if (array_key_exists('fleets', $incfleetval))
    			{
    				$fleetscore["fleetid"] = $incfleetval["id"];
    				$fleetscore["budget"] = (float)0;
    				$fleetscore["budgetcontrib"] = (float)0;
    				$fleetscore["budkms"] = (float)0;
    				$fleetscore["blackouts"] = (float)0;
    				$fleetscore["day"] = $i;
    				$fleetscore["date"] = strtotime(date("Y-m-".(strlen($i) === 1 ? "0".$i : $i)));
    				$fleet_key = array_keys($incfleetval['fleets']);
    				$fleet_size = sizeOf($key);
    				//foreach ($incfleetval['fleets'] as $key=>$val)
    				for ($k=0; $k<$fleet_size; $k++)
    				{
    					if (array_key_exists($k, $fleet_key) === FALSE)
    					{
    						syslog(LOG_INFO, "Line 788: Key doesn't exist in array: ".$j);
    						continue;
    					}
    					if (array_key_exists($k, $fleet_key) === TRUE)
    					{
    						if (array_key_exists($fleet_key[$k], $incfleetval['fleets']) === FALSE)
    						{
    							syslog(LOG_INFO, "Line 795: Trying to get income for a non-exsistent array key: ".$fleet_key[$k]);
    							continue;
    						}
    					}
    					$val = $incfleetval['fleets'][$fleet_key[$k]];
    					//print_r($val);
    					$record = sqlPull(array(
    						"onerow"=>TRUE,
    						"table"=>"fleet_scores",
    						"where"=>"`fleetid`=".$val[0]." AND `date`=".strtotime(date("Y-m-".(strlen($i) === 1 ? "0".$i : $i)))
    						));
    					print_r($record);
    					if ($record)
    					{
    						$fleetscore["budget"] += $record['budget'];
    						$fleetscore["budgetcontrib"] += $record['budgetcontrib'];
    						$fleetscore["budkms"] += $record['budkms'];
    						$fleetscore["blackouts"] += $record['blackouts'];
    					}
    				}
    				// print_r($fleetscore);
    			}
    			else
    			{
    				//: Skip t24 entries
    				if (array_key_exists("t24", $incfleetval))
    				{
    					continue;
    				}
    				$blackoutcount = (float)0;
    				$daybudget = (float)0;
    				$daybudkms = (float)0;
    				$daybudgetcontrib = (float)0;
    				//: Get the data
    				$startDate = (string)date("Y-m-".(strlen($i) === 1 ? "0".$i : $i));
    				$stopDate = (string)"";
    				if (($i == date("t")) && (date("m") == 12)) {
    					$stopDate = date("Y-m-d", mktime(0, 0, 0, 1, 1, date("Y")+1));
    				} elseif ($i == date("t")) {
    					$stopDate = date("Y-".date("m", strtotime("+1 month"))."-01");
    				} else {
    					if ($i === 9)
    					{
    						$stopDate = date("Y-m-".(strlen($i) === 1 ? ($i+1) : $i+1));
    					}
    					else
    					{
    						$stopDate = date("Y-m-".(strlen($i) === 1 ? "0".($i+1) : $i+1));
    					}
    				}
    				
    				$budgeturl = $this->_apiurl."report=85&responseFormat=csv&Start_Date=".$startDate."&Stop_Date=".$stopDate."&Fleet=".$incfleetval["maxid"];
    				print("budgeturl: ".$budgeturl.PHP_EOL);
    				$budgetdata = $this->getDataFromFile($budgeturl, "budget".$incfleetval["id"].'_'.date('d', strtotime($startDate)).".csv");
    				if ($budgetdata === false) {
    					syslog(LOG_INFO, "Line 818: No data returned from fleetDayHandler::getDataFromFile()");
    					continue;
    				}
    				//: key the array on the truck fleet number so that we remove any duplicated trucks in output
    				$interim = (array)array();
    				foreach ($budgetdata as $val)
    				{
    					$interim[$val['Truck']] = $val;
    				}
    				unset($budgetdata);
    				//: End
    				//: Collate data
    				foreach ($interim as $budgetkey=>$budgetval) {
    					if (array_key_exists('trucks', $incfleetval))
    					{
    						if (array_key_exists('Truck', $budgetval))
    						{
    							if (in_array($budgetval['Truck'], $incfleetval['trucks']) === FALSE)
    							{
    								continue;
    							}
    						}
    					}
    					$truckbudget = isset($budgetval["Income"]) ? str_replace(",", "", $budgetval["Income"]) : "";
    					$truckbudget = str_replace("R", "", $truckbudget);
    					$daybudget += $truckbudget;
    					
    					$truckbudgetcontrib     = isset($budgetval["Contribution"]) ? str_replace(",", "", $budgetval["Contribution"]) : "";
    					$truckbudgetcontrib     = str_replace("R", "", $truckbudgetcontrib);
    					$daybudgetcontrib += $truckbudgetcontrib;
    					
    					$daybudkms += isset($budgetval["Kms"]) ? $budgetval["Kms"] : 0;
    					
    					// Calculate the number of trucks per fleet that have a budget and no trip
    					if(isset($budgetval["Blackout Status"]) && (($budgetval["Blackout Status"] == "1") || ($budgetval["Blackout Status"] == "Yes"))) {
    						if($truckbudget > 0) {
    							$blackoutcount++;
    						}
    					}
    				}
    				//: End
    				//: Insert or update
    				$fleetscore = (array)array();
    				$fleetscore["fleetid"] = $incfleetval["id"];
    				$fleetscore["budget"] = $daybudget;
    				$fleetscore["budgetcontrib"] = $daybudgetcontrib;
    				$fleetscore["budkms"] = $daybudkms;
    				$fleetscore["day"] = $i;
    				$fleetscore["date"] = strtotime(date("Y-m-".(strlen($i) === 1 ? "0".$i : $i)));
    				# $fleetscore["updated"] = date("U");
    				$fleetscore["blackouts"] = $blackoutcount;
    			}
    			//: check to see if this data needs to be updated or if it can just be inserted
    			$record = sqlPull(array(
    				"onerow"=>TRUE,
    				"table"=>"fleet_scores",
    				"where"=>"`fleetid`=".$fleetscore["fleetid"]." AND `date`=".$fleetscore["date"]
    				));
    			if (isset($record) && $record) { //: Update
    				sqlCommit(array(
    					"table"=>"fleet_scores",
    					"where"=>"id=".$record["id"],
    					"fields"=>$fleetscore
    					));
    			} else { //: Insert
    				sqlCreate(array(
    					"table"=>"fleet_scores",
    					"fields"=>$fleetscore
    					));
    			}
    			//: End
    		}
    	}
    }
    //: End
    
    public function saveFleetDay($fleetscore) {
    	// Create or commit records to database {
    	$record     = sqlPull(array("table"=>"fleet_scores", "where"=>"date=".$this->_date, "customkey"=>"fleetid"));
    	$check = (array)array(
    		"income","contrib","kms","budget","budgetcontrib","budkms"
    		);
    	foreach ($fleetscore as $fleetkey=>$fleetval) {
    		if($record[$fleetkey]) {
    			// if ($this->confirmFleetScoreData($record[$fleetkey], $fleetval) === FALSE) {
    			// continue;
    			// }
    			//sqlDelete(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleetkey." AND day=".$this->_day));
    			//sqlCreate(array("table"=>"fleet_scores", "fields"=>$fleetval));
    			sqlCommit(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleetkey." AND date=".$this->_date, "fields"=>$fleetval));
    		} else {
    			sqlCreate(array("table"=>"fleet_scores", "fields"=>$fleetval));
    		}
    	}
    	// }
    }
    
    public function getFleetScoreDay($fleet) {
    	$date             = mktime(0,0,0,date("m"), date("d"), date("Y"));
    	
    	$fleetday   = sqlPull(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleet." AND date=".$date, "sort"=>"day", "customkey"=>"day"));
    	
    	//$fleetday = $this->useArtificialBudgets($fleet, $fleetday);
    	
    	return $fleetday;
    }
    
    public function getFleetScoreMonth($fleet) {
    	$startdate  = mktime(0, 0, 0, date("m"), 1, date("Y"));
    	$enddate          = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    	/*
    	print("<div style='color:WHITE;'>");
    	print("Start Date: ".date("d m Y", $startdate)." ".$startdate."<br>");
    	print("End Date ".date("d m Y", $enddate)." ".$enddate."<br>");
    	print("</div>");
    	*/
    	
    	$day                    = date("d");
    	
    	$fleetdays  = sqlPull(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleet." AND date>=".$startdate." AND date<=".$enddate, "sort"=>"day", "customkey"=>"day"));
    	
    	//$fleetdays      = $this->useArtificialBudgets($fleet, $fleetdays);
    	
    	return $fleetdays;
    }
    
    public function calcSliderTop($budget) {
    	$slidertop  = 0;
    	$increment  = 5000;
    	$margin                 = $budget + $increment / 5;
    	while($slidertop < $margin) {
    		if($slidertop > ($increment * 10)) {
    			$increment *= 2;
    		}
    		
    		$slidertop  += $increment;
    	}
    	return $slidertop;
    }
    
    //: Slider functions
    /** fleetdayHandler::createSlider(array $data)
    * Create a new slider
    * @param array data -> slider data
    * @param int $userid. Which user is this slider for?
    * @return TRUE on success. FALSE otherwise.
    */
    public function createSlider(array $data, $userid)
    {
    	//: Tests
    	if (is_int($userid) === FALSE)
    	{
    		syslog(LOG_INFO, 'Parameter userid passed to fleetDayHandler::createSlider is of an invalid type');
    		return FALSE;
    	}
    	//: End 
    	//: Create the Slider
    	$slider = (array)array(
    		'name'=>$data['slide_name'],
    		'users_id'=>(isset($_SESSION['userid']) ? $_SESSION['userid'] : 0)
    		);
    	$id = sqlCreate(array("table"=>"sliders", "fields"=>$slider));
    	if (!$id)
    	{
    		return FALSE;
    	}
    	//: End
    	//: Create the sliders_fleets
    	$fleets = preg_split('/,/', $data['fleet_ids']);
    	foreach ($fleets as $val)
    	{
    		$record = (array)array(
    			'slider_id'=>$id,
    			'fleet_id'=>$val
    			);
    		sqlCreate(array('table'=>'sliders_fleets', 'fields'=>$record));
    		unset($record);
    	}
    	//: End
    	//: Append slider fleet to user_dashboards
    	$sql = (string)'SELECT * FROM `user_dashboards` WHERE `userid`='.$userid;
    	$data = sqlQuery($sql);
    	if (array_key_exists(0, $data) === FALSE)
    	{
    		return FALSE;
    	}
    	if (array_key_exists('id', $data[0]) === FALSE)
    	{
    		return FALSE;
    	}
    	$pattern = preg_split('/\;/', $data[0]['pattern']);
    	$pattern[] = $id;
    	$update = (array)array(
    		'userid'=>$data[0]['userid'],
    		'duration'=>$data[0]['duration'],
    		'pattern'=>implode(';', $pattern)
    		);
    	commitMyDashboard($update);
    	//: End
    	return TRUE;
    }
    
    /** fleetdayHandler::updateSlider(array $data)
    *
    */
    public function updateSlider(array $data)
    {
    	//print_r($data);
    	//: Update the sliders table
    	sqlCommit(array("table"=>"sliders", "where"=>"id=".$data['id'], 'fields'=>array('name'=>$data['slide_name'])));
    	//: End
    	//: Delete all of the linked fleets
    	sqlDelete(array('table'=>'sliders_fleets', 'where'=>'slider_id='.$data['id']));
        
    	//: End
    	//: Create new slider fleets
    	$fleets = preg_split('/,/', $data['fleet_ids']);
    	foreach ($fleets as $val)
    	{
    		$record = (array)array(
    			'slider_id'=>$data['id'],
    			'fleet_id'=>$val
    			);
    		sqlCreate(array('table'=>'sliders_fleets', 'fields'=>$record));
    		unset($record);
    	}
    	//: End
    	return TRUE;
    	
    }
    
    /** fleetdayHandler::deleteSlider($id)
    * Delete a slider
    * @param INT $slider which slider do we delete?
    * @return BOOL TRUE on success. FALSE otherwise.
    */
    public function deleteSlider($id)
    {
    	//: Tests
    	if (is_int($id) === FALSE)
    	{
    		return FALSE;
    	}
    	//: End
    	//: Confirm record exists
    	$sql = (string)'SELECT * FROM `sliders` WHERE id='.$id;
    	$data = (array)sqlQuery($sql);
    	if (array_key_exists(0, $data) === FALSE)
    	{
    		return FALSE;
    	}
    	if (array_key_exists('id', $data[0]) === FALSE)
    	{
    		return FALSE;
    	}
    	syslog(LOG_INFO, 'slider deleted: '.serialize($data[0]));
    	//: End
    	//: Delete it
    	sqlDelete(array('table'=>'sliders', 'where'=>'id='.$data[0]['id']));
    	//: End
    	return TRUE;
    }
    
    /** fleetDayHandler::getSlideFleets($slide_id)
    * Get an array of fleets that belong to this slider
    * @param INT $slide_id which slider are we looking for?
    * @return array data on success FALSE otherwise
    */
    public function getSlideFleets($slide_id)
    {
    	//: Tests
    	if (is_int($slide_id) === FALSE)
    	{
    		syslog(LOG_INFO, 'Parameter passed to fleetDayHandler::getSlideFleets is invalid');
    		return FALSE;
    	}
    	//: End
    	$return = (array)array();
    	//: Confirm record exists
    	$sql = (string)'SELECT * from `sliders` WHERE id='.$slide_id;
    	$data = (array)sqlQuery($sql);
    	if (array_key_exists(0, $data) === FALSE)
    	{
    		syslog(LOG_INFO, 'Query returned no results');
    		return FALSE;
    	}
    	if (array_key_exists('id', $data[0]) === FALSE)
    	{
    		syslog(LOG_INFO, 'Query didn\'t return a result');
    		return FALSE;
    	}
    	//: End
    	$return = $data[0];
    	unset($data);
    	$sql = (string)'SELECT * FROM `sliders_fleets` WHERE `slider_id`='.$slide_id;
    	$fleets = (array)sqlQuery($sql);
    	if (array_key_exists(0, $fleets) === FALSE)
    	{
    		syslog(LOG_INFO, 'Query returned no results');
    		return FALSE;
    	}
    	if (array_key_exists('id', $fleets[0]) === FALSE)
    	{
    		syslog(LOG_INFO, 'Query didn\'t return a result');
    		return FALSE;
    	}
    	$return['fleets'] = $fleets;
    	return $return;
    }
    
    /**fleetdayHandler::getSliderName($slide_id)
    * get a specific sliders name
    * @param INT $slide_id what is the slider we are searching on?
    * @return STRING $slide_name on success, FALSE on failure
    */
    public function getSliderName($slide_id)
    {
    	//: Tests
    	if (is_int($slide_id) === FALSE)
    	{
    		syslog(LOG_INFO, 'Parameter passed to fleetDayHandler::getSliderName is invalid');
    		return "not int";
    	}
    	//: End
    	//: Confirm record exists
    	$sql = (string)'SELECT * from `sliders` WHERE id='.$slide_id;
    	$data = (array)sqlQuery($sql);
    	if (array_key_exists(0, $data) === FALSE)
    	{
    		syslog(LOG_INFO, 'Query returned no results');
    		return "no result 1";
    	}
    	if (array_key_exists('id', $data[0]) === FALSE)
    	{
    		syslog(LOG_INFO, 'Query didn\'t return a result');
    		return "no result 2";
    	}
    	//: End
    	if ((array_key_exists('name', $data[0]) !== FALSE) && $data[0]['name'])
    	{
    		return $data[0]['name'];
    	}
    	else
    	{
    		return "no result 3";
    	}
    }
    
    /** fleetDayHandler::getUserSliders($user_id)
    * get a list of sliders llinked to this user
    * @param INT $user_id which user do we get sliders for?
    * @return array data on success, FALSE otherwise
    */
    public function getUserSliders($user_id, $last_one = FALSE)
    {
    	//: Tests
    	if (is_int($user_id) === FALSE)
    	{
    		syslog(LOG_INFO, 'Parameter passed to fleetDayHandler::getUserSliders is invalid');
    		return FALSE;
    	}
    	//: End
    	//: Get User Sliders
    	$sql = (string)'SELECT `s`.`id`, `s`.`name`, `sf`.`fleet_id` FROM `sliders` AS `s` LEFT JOIN `sliders_fleets` AS `sf` ON `s`.`id`=`sf`.`slider_id` WHERE `users_id`='.$user_id.' ORDER BY `id` DESC';
    	$data = sqlQuery($sql);
    	if ($data === FALSE)
    	{
    		syslog(LOG_INFO, 'Query returned no results. Query run: '.$sql);
    		return array('Query failed to return results.');
    	}
    	$records = (array)array();
    	foreach ($data as $row)
    	{
    		$records[$row['id']][] = $row;
    	}
    	if ($last_one === TRUE)
    	{
    		$keys = array_keys($records);
    		return $records[$keys[0]];
    	}
    	else
    	{
    		return $records;
    	}
    }
    //: End
    
    
    //: Refuels
    /** getUnauthorizedRefuels($test = FALSE)
    * @param BOOL $test is this a request for test platform?
    * @return TRUE on success. FALSE otherwise
    */
    public function getUnauthorizedRefuels($test = FALSE)
    {
    	$fleets = $this->_incomefleets;
    	$data = (array)array();
    	foreach ($fleets as $val)
    	{
    		if (array_key_exists('fleets', $val))
    		{
    			$data[$val['maxid']]['fleet_id'] = $val['maxid'];
    			$data[$val['maxid']]['count'] = 0;
    			$data[$val['maxid']]['litres'] = 0;
    			$subFleets = (string)'';
    			foreach ($val['fleets'] as $subfleets)
    			{
    				$subFleets .= $subfleets[0].',';
    			}
    			$sql = (string)'SELECT SUM(`count`) AS `count`, SUM(`litres`) AS `litres` ';
    			$sql .= 'FROM `unauthorized_refuels` ';
    			$sql .= 'WHERE `fleet_id` IN ('.substr($subFleets, 0, -1).')';
    			$row = sqlQuery($sql);
    			if ($row === FALSE)
    			{
    				continue;
    			}
    			$data[$val['maxid']]['count'] = $row[0]['count'];
    			$data[$val['maxid']]['litres'] = $row[0]['litres'];
    		}
    		else
    		{
    			$unauthurl = $this->_apiurl."report=77&responseFormat=csv&Fleet=".$val['maxid']."&Start%20Date=".date("Y-m-d", strtotime('-30 days'))."&Stop%20Date=".date("Y-m-01", strtotime("+1 month"));
    			if ($test === TRUE)
    			{
    				$unauthurl = preg_replace('/https\:\/\/login\.max\.bwtsgroup\.com/', 'http://max.mobilize.biz', $unauthurl);
    			}
    			// print($unauthurl);
    			$fileParser = new FileParser($unauthurl);
    			$fileParser->setCurlFile("unauthrefuels.csv");
    			$refueldata = $fileParser->parseFile();
    			if ($refueldata === FALSE)
    			{
    				print("<pre style='font-family:verdana;font-size:13'>");
    				print_r($fileParser->getErrors());
    				print("</pre>");
    				return FALSE;
    			}
    			if (is_array($refueldata) === FALSE)
    			{
    				return FALSE;
    			}
    			$data[$val['maxid']]['fleet_id'] = $val['maxid'];
    			$data[$val['maxid']]['count'] = 0;
    			$data[$val['maxid']]['litres'] = 0;
    			foreach ($refueldata as $rec)
    			{
    				$data[$val['maxid']]['count'] += 1;
    				$data[$val['maxid']]['litres'] += ($rec['Litres'] == '(none' ? 0 : $rec['Litres']);
    			}     
    		}
    	}
    	/* print('<pre>');
    	print_r($data);
    	print('</pre>'); */
    	foreach ($data as $key=>$val)
    	{
    		$record = sqlPull(array("table"=>"unauthorized_refuels", "where"=>"fleet_id=".$key, "customkey"=>"fleet_id"));
    		if (array_key_exists($key, $record) && $record[$key])
    		{
    			sqlCommit(array("table"=>"unauthorized_refuels", "where"=>"fleet_id=".$key, "fields"=>$val));
    		}
    		else
    		{
    			sqlCreate(array("table"=>"unauthorized_refuels", "fields"=>$val));
    		}
    	}
    }
    
    /** refuel($test = FALSE)
    * get open and missing refuel data
    * @param BOOL $test is this a request for test platform?
    * @return TRUE on success. FALSE otherwise
    */
    public function refuel($test = FALSE)
    {
    	$fleets = $this->_incomefleets;
    	$data = (array)array();
    	foreach ($fleets as $val)
    	{
    		$data[$val['maxid']]['fleet_id'] = $val['maxid'];
    		if (array_key_exists('fleets', $val))
    		{
    			$sql = (string)'SELECT SUM(`fleet_count`) AS `fleet_count`, SUM(`missing_count`) AS `missing_count`, SUM(`open_count`) AS `open_count`, SUM(`total_open_count`) AS `total_open_count` ';
    			$sql .= 'FROM `refuels` ';
    			$fleetsToLoop = (array)array();
    			foreach ($val['fleets'] as $ook)
    			{
    				$fleetsToLoop[] = $ook[0];
    			}
    			$sql .= 'WHERE `fleet_id` IN('.implode($fleetsToLoop, ',').')';
    			$row = sqlQuery($sql);
    			if ($row === FALSE)
    			{
    				continue;
    			}
    			$data[$val['maxid']]['fleet_count'] = $row[0]['fleet_count'];
    			$data[$val['maxid']]['missing_count'] = $row[0]['missing_count'];
    			$data[$val['maxid']]['open_count'] = $row[0]['open_count'];
    			$data[$val['maxid']]['total_open_count'] = $row[0]['total_open_count'];
    		}
    		else
    		{
    			//: Get fleet count
    			$trucks = $this->_apiurl."report=145&responseFormat=csv&Fleet=".$val['maxid']."&Start%20Date=".date("Y-m-d")."&Stop%20Date=".date("Y-m-d", strtotime("+1 day"));
    			if ($test === TRUE)
    			{
    				$trucks = preg_replace('/https\:\/\/login\.max\.bwtsgroup\.com/', 'http://max.mobilize.biz', $trucks);
    			}
    			$fileParser = new FileParser($trucks);
    			$fileParser->setCurlFile("trucks.".$val['maxid'].".csv");
    			$trucks = $fileParser->parseFile();
    			if ($trucks === FALSE)
    			{
    				print("<pre style='font-family:verdana;font-size:13'>");
    				print_r($fileParser->getErrors());
    				print("</pre>");
    				return FALSE;
    			}
    			if (is_array($trucks) === FALSE)
    			{
    				return FALSE;
    			}
    			$data[$val['maxid']]['fleet_count'] = count($trucks);
    			//: End
    			$refuels = $this->_apiurl."report=175&responseFormat=csv&Fleet=".$val['maxid']."&Start%20Date=".date("Y-m-d", strtotime('-30 days'))."&Stop%20Date=".date("Y-m-01", strtotime("+1 month"));
    			if ($test === TRUE)
    			{
    				$refuels = preg_replace('/https\:\/\/login\.max\.bwtsgroup\.com/', 'http://max.mobilize.biz', $refuels);
    			}
    			print($refuels.PHP_EOL);
    			$fileParser = new FileParser($refuels);
    			$fileParser->setCurlFile("missingrefuels".$val['maxid'].".csv");
    			$missingrefuels = $fileParser->parseFile();
    			if ($missingrefuels === FALSE)
    			{
    				print("<pre style='font-family:verdana;font-size:13'>");
    				print_r($fileParser->getErrors());
    				print("</pre>");
    				return FALSE;
    			}
    			if (is_array($missingrefuels) === FALSE)
    			{
    				return FALSE;
    			}
    			// print_r($missingrefuels);
    			$i = (int)0;
    			//: Missing Refuels
    			foreach ($missingrefuels as $key => $value) {
    				//: Checks
    				if ($val['maxid'] == 73)
    				{
    					print_r($value);
    				}
    				if (($value['Odometer'] === '(none)') || ($value['Odometer'] === ''))
    				{
    					continue;
    				}
    				if (substr($value['Variance'], 0, 1) !== '-')
    				{
    					continue;
    				}
    				//: End
    				if (($value['Variance']*-1) >= $val['kms_limit'])
    				{
    					$i++;
    				}
    			}
    			$data[$val['maxid']]['missing_count'] = $i;
    			//: End
    			//: Open Refuels
    			$refuels = $this->_apiurl."report=174&responseFormat=csv&Fleet=".$val['maxid']."&Start%20Date=".date("Y-m-d", strtotime('-30 days'))."&Stop%20Date=".date("Y-m-01", strtotime("+1 month"));
    			if ($test === TRUE)
    			{
    				$refuels = preg_replace('/https\:\/\/login\.max\.bwtsgroup\.com/', 'http://max.mobilize.biz', $refuels);
    			}
    			print($refuels.PHP_EOL);
    			$fileParser = new FileParser($refuels);
    			$fileParser->setCurlFile("openrefuels".$val['maxid'].".csv");
    			$openrefuels = $fileParser->parseFile();
    			if ($openrefuels === FALSE)
    			{
    				print("<pre style='font-family:verdana;font-size:13'>");
    				print_r($fileParser->getErrors());
    				print("</pre>");
    				return FALSE;
    			}
    			if (is_array($openrefuels) === FALSE)
    			{
    				return FALSE;
    			}
    			// print_r($openrefuels);
    			$cnt = (int)0;
    			foreach ($openrefuels as $key => $value) {
    				//: Convert the refuel time difference into seconds
    				$refuel_time = (int)0;
    				$split = preg_split('/\s/', $value['Duration Open (Refuel Time)']);
    				if (array_key_exists(0, $split))
    				{
    					if (substr($split[0], -1, 1) === 'd')
    					{
    						//: Days
    						$refuel_time += (24*60*60)*(int)$split[0];
    					}
    					else
    					{
    						//: Hours
    						$refuel_time += (60*60)*(int)$split[0];
    					}
    				}
    				if (array_key_exists(1, $split))
    				{
    					//: Hours
    					$refuel_time += (60*60)*(int)$split[0];
    				}
    				
    				// print('refuel time: '.$refuel_time.PHP_EOL);
    				if ($refuel_time >= $val['open_time'])
    				{
    					$cnt++;
    				}
    				//: End
    			}
    			$data[$val['maxid']]['total_open_count'] = count($openrefuels);
    			$data[$val['maxid']]['open_count'] = $cnt;
    			//: End     
    		}
    		
    	}
    	foreach ($data as $key=>$val)
    	{
    		$record = sqlPull(array("table"=>"refuels", "where"=>"fleet_id=".$key, "customkey"=>"fleet_id"));
    		if (array_key_exists($key, $record) && $record[$key])
    		{
    			sqlCommit(array("table"=>"refuels", "where"=>"fleet_id=".$key, "fields"=>$val));
    		}
    		else
    		{
    			sqlCreate(array("table"=>"refuels", "fields"=>$val));
    		}
    		print('|');
    	}
    }
    //: End
    
    //: Private Functions
    /** confirmFleetScoreData(array $current, array $proposed)
    * Sanity check data to be updated
    * @param array $current Current data set from `DB_SCHEMA`.`fleets_scores`
    * @param array $proposed Data to be used to update
    * @return TRUE if all good FALSE on failure
    */
    private function confirmFleetScoreData(array $current, array $proposed) {
    	$check = (array)array(
    		"income","contrib","kms","budget","budgetcontrib","budkms"                          
    		);
    	$public_holidays = (array)array(
    		2012=>array("2012-01-01","2012-01-02","2012-03-21","2012-04-06","2012-04-09","2012-04-27","2012-05-01","2012-06-16","2012-08-09","2012-09-24","2012-12-16","2012-12-17","2012-12-25","2012-12-26"),
    		2013=>array("2012-01-01","2012-03-21","2012-03-29","2012-04-01","2012-04-27","2012-05-01","2012-06-16","2012-06-17","2012-08-09","2012-09-24","2012-12-16","2012-12-25","2012-12-26")
    		);
    	foreach ($check as $val) {
    		//: If the data we are trying to update doesn't exist skip the column
    		if (!isset($proposed[$val])) {continue;}
    		switch ($val) {
    		case "income":
    		case "contrib":
    		case "kms":
    			if (!$proposed[$val] && $current[$val]) {return FALSE;}
    			break;
    		}
    		if ((date("w", $this->_date) !== 0) && (!in_array(date("Y-m-d", $this->_date), $public_holidays[date("Y", $this->_date)]))) {
    			switch ($val) {
    			case "budget":
    			case "budgetcontrib":
    			case "budkms":
    				if (!$proposed[$val] && $current[$val]) {return FALSE;}
    				break;
    			}
    		}
    	}
    	return TRUE;
    }
    
    private function getDataFromFile($url, $file=NULL)
    {
    	if ($this->_fileParser)
    	{
    		$fileParser = $this->_fileParser;
    	}
    	else
    	{
    		$fileParser = new FileParser();
    		$this->_fileParser = $fileParser;
    	}
    	$fileParser->doStartUp($url);
    	if ($file)
    	{
    		$fileParser->setCurlFile($file);
    	}
    	$data = $fileParser->parseFile();
    	if ($data === FALSE) {
    		print("<pre style='font-family:verdana;font-size:13'>");
    		print_r($fileParser->getErrors());
    		print("</pre>");
    		return;
    	}
    	return $data;
    }
    
    private function useArtificialBudgets($fleet, $fleetdays) {
    	$pubholidays      = array(41=>41);
    	
    	$month                        = date("m");
    	$year                         = date("Y");
    	$fleetnumber      = 0;
    	foreach ($this->_incomefleets as $fleetkey=>$fleetval) {
    		if($fleetval["id"] == $fleet) {
    			$fleetnumber      = $fleetkey;
    		}
    	}
    	
    	foreach ($fleetdays as $daykey=>$dayval) {
    		$day  = $dayval["day"];
            
    		$weekday          = date("w", mktime(0,0,1,$month,$day,$year));
            
    		$daybudget  = 0;
    		$daybudget  = $this->_incomefleets[$fleetnumber]["budget"];
            
            
    		if(($this->_incomefleets[$fleetnumber]["pubhol"] == 1) && ($pubholidays[$day])) {
    			$daybudget  = 0; // This fleet is not budgetted to make income on Public holidays
    		} else if(($weekday == 6) || ($weekday == 0)) {
    			$daybudget  = ($daybudget / 2);
    		}
            
    		$fleetdays[$daykey]["budget"] = $daybudget;
    		$fleetdays[$daykey]["budkms"] = $this->_incomefleets[$fleetnumber]["budkms"];
    	}
    	
    	return $fleetdays;
    }
    //: End
} // This is the end of the class.  Do not put class functions after it.
?>