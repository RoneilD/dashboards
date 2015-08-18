<?PHP
/** Class::fleetDayHandler
* @author Justin Ward
* @author justinw@manlinegroup.com
* @copyright 2010 Manline Group (Pty) Ltd

* @requires This class requires access to the FileParser class. 

* @example First Example Start
* require_once(BASE.'basefunctions/baseapis/fleetDayHandler.php');
* $fleetdayobj	= new fleetDayHandler();
* $today	= date("d");
* $fleetscore		= $fleetdayobj->pullFleetDay($today);
* @example First Example End
**/
require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");

class fleetDayHandler {
        protected $_incomefleets = array(
                array('id'=>1, "maxid"=>29, "name"=>"Entire Active Fleet", "budget"=>1303701, "budkms"=>103011, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                array('id'=>2, "maxid"=>28, "name"=>"Long Distance", "budget"=>508752, "budkms"=>44705, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                array('id'=>3, "maxid"=>51, "name"=>"LWT Fleet", "budget"=>86894, "budkms"=>7173, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                array('id'=>4, "maxid"=>81, "name"=>"Freight Haz Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                //array('id'=>5, "maxid"=>47, "name"=>"Isando - Reclam Triaxles", "budget"=>79539, "budkms"=>4903, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                //array('id'=>6, "maxid"=>50, "name"=>"Energy - Flat Decks", "budget"=>92133, "budkms"=>6953, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                array('id'=>7, "maxid"=>32, "name"=>"Energy - Tankers", "budget"=>44016, "budkms"=>3313, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                array('id'=>8, "maxid"=>53, "name"=>"Energy - BHP Tankers", "budget"=>117358, "budkms"=>7213, "pubhol"=>0, "display"=>1, "displayblackouts"=>1, "displayrasta"=>0),
                array('id'=>9, "maxid"=>86, "name"=>"Total Sishen Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>0, "display"=>1, "displayblackouts"=>0, "displayrasta"=>1),
                array('id'=>10, "maxid"=>42, "name"=>"Energy - Buckman", "budget"=>43112, "budkms"=>2962, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                array('id'=>11, "maxid"=>87, "name"=>"Energy - Ecosse", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>1),
                array('id'=>12, "maxid"=>121, "name"=>"Energy - NCP Chlorine", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>1),
                array('id'=>13, "maxid"=>126, "name"=>"Energy - NCP Other", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>14, "maxid"=>124, "name"=>"Energy - NCP Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>15, "maxid"=>123, "name"=>"Energy - Easigas", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>1),
                // array('id'=>16, "maxid"=>54, "name"=>"XB - Links", "budget"=>300880, "budkms"=>23117, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                // array('id'=>17, "maxid"=>35, "name"=>"XB - Triaxles", "budget"=>22220, "budkms"=>1722, "pubhol"=>1, "display"=>1, "displayblackouts"=>1, "displayrasta"=>1),
                array('id'=>18, "maxid"=>73, "name"=>"Africa - Copperbelt Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>1),
                array('id'=>19, "maxid"=>52, "name"=>"Africa - Links Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>1),
                array('id'=>20, "maxid"=>72, "name"=>"Africa - Triaxle Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>1),
                array('id'=>21, "maxid"=>133, "name"=>"Africa - UD Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>22, "maxid"=>82, "name"=>"Wilmar Bulk Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>1),
                array('id'=>23, "maxid"=>67, "name"=>"Long Distance - Jimmy", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                // array('id'=>24, "maxid"=>68, "name"=>"Long Distance - Kevin", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>25, "maxid"=>69, "name"=>"Long Distance - Kershan", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>26, "maxid"=>83, "name"=>"Ashton Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>1),
                // array('id'=>27, "maxid"=>71, "name"=>"XB - Wilson", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>0),
                // array('id'=>28, "maxid"=>79, "name"=>"XB - Devon", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>0),
                // array('id'=>29, "maxid"=>73, "name"=>"XB - Wessel", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>0),
                // array('id'=>30, "maxid"=>84, "name"=>"XB - Rhamba", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0, "displayblackouts"=>0, "displayrasta"=>0),
                // The count in displayMainDash controls how far down this list the dashboards go.  Fleets after this point won't appear in normal cycles
                array('id'=>31, "maxid"=>60, "name"=>"Manline Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>0),
                // array('id'=>32, "maxid"=>75, "name"=>"XB - 7/11 Links", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>33, "maxid"=>33, "name"=>"Energy (Own)", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>34, "maxid"=>76, "name"=>"Freight SA (Own)", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>35, "maxid"=>77, "name"=>"Freight Africa", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>36, "maxid"=>102, "name"=>"Ellerines - Gauteng", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>37, "maxid"=>103, "name"=>"Ellerines - P.E", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>38, "maxid"=>168, "name"=>"Illovo - Germiston CCC", 'trucks'=>array('144000', '144002', '144003'), 'customer'=>array('Coca Cola Canners'), "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>39, "maxid"=>168, "name"=>"Illovo - Germiston F and V", 'trucks'=>array('444069','444070','444071','444072','444073','D550'), 'customer'=>array('Illovo Refined GBD F and V'), "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>40, "maxid"=>113, "name"=>"Meadow Feeds - PMB", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>41, "maxid"=>104, "name"=>"Ellerines Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>42, "maxid"=>168, "name"=>"Toyota - X Dock", 'trucks'=>array('525199','525200','525201','525250','525251','525252','545249','545250','545251','545252','545253','545254','Hire1','Hire2','Hire3','Hire4'), 'customer'=>array('Toyota X-Dock F & V', 'Toyota X-Dock'), "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>43, "maxid"=>101, "name"=>"Toyota Tsusho", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>44, "maxid"=>106, "name"=>"Toyota - Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>45, "maxid"=>99, "name"=>"Illovo - Avoca", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>46, "maxid"=>114, "name"=>"PPC - Hercules", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>47, "maxid"=>115, "name"=>"PPC - Heriotdale", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>48, "maxid"=>108, "name"=>"Meadow Feeds - Paarl", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>49, "maxid"=>116, "name"=>"PPC - Slurry", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>50, "maxid"=>118, "name"=>"Meadow Feeds - Delmas", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>51, "maxid"=>100, "name"=>"Corobrick - Avoca", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>52, "maxid"=>112, "name"=>"Meadow Feeds - P.E", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>53, "maxid"=>109, "name"=>"PPC - Kraaifontein", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>54, "maxid"=>110, "name"=>"PPC - George", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>55, "maxid"=>111, "name"=>"PPC - P.E", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>56, "maxid"=>160, "name"=>"RE - Cape", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>57, "maxid"=>159, "name"=>"RE - Denver", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>58, "maxid"=>158, "name"=>"RE - KZN", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>59, "maxid"=>98, "name"=>"RE- Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>60, "maxid"=>117, "name"=>"PPC - Dwaalboom", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>61, "maxid"=>131, "name"=>"PPC - Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>62, "maxid"=>150, "name"=>"Manline Mega", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>63, "maxid"=>91, "name"=>"Anglo - Dedicated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>64, "maxid"=>92, "name"=>"Anglo - Sub Contractor", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>65, "maxid"=>93, "name"=>"Anglo Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>66, "maxid"=>119, "name"=>"Meadow Feeds - Randfontein", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>67, "maxid"=>169, "name"=>"Meadow Feeds - Standerton", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>68, "maxid"=>132, "name"=>"Meadow Feeds - Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>69, "maxid"=>151, "name"=>"Premier Durban", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>70, "maxid"=>152, "name"=>"Premier East London", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>71, "maxid"=>153, "name"=>"Premier Empangeni", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>72, "maxid"=>155, "name"=>"Premier Newcastle", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>73, "maxid"=>156, "name"=>"Premier Pietermaritzburg", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>74, "maxid"=>163, "name"=>"Premier Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>75, "maxid"=>335, "name"=>"T24 - Vryheid Adhoc", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>76, "maxid"=>354, "name"=>"T24 - Adhoc", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>77, "maxid"=>328, "name"=>"T24 - Merensky", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>78, "maxid"=>334, "name"=>"T24 - Mondi", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>79, "maxid"=>332, "name"=>"T24 - Sappi KZN", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>80, "maxid"=>165, "name"=>"Anglo - Mokopane Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>81, "maxid"=>166, "name"=>"Anglo - Rustenburg", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>82, "maxid"=>168, "name"=>"Commercial", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>83, "maxid"=>206, "name"=>"Manline Mega - 75 Tons", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>84, "maxid"=>207, "name"=>"Manline Mega - 55 Tons", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>85, "maxid"=>208, "name"=>"Manline Mega - 80/90/100 Tons", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>86, "maxid"=>209, "name"=>"Manline Mega - 45 Tons", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>87, "maxid"=>210, "name"=>"Manline Mega - 40 Tons", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>88, "maxid"=>212, "name"=>"Manline Mega - Standards", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>89, "maxid"=>211, "name"=>"Manline Mega - 14 Tons", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>90, "maxid"=>222, "name"=>"Construction", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>91, "maxid"=>333, "name"=>"T24 - Sappi MPU", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>92, "maxid"=>170, "name"=>"Idwala", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>93, "maxid"=>223, "name"=>"Manline Mega - Fleet 1", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>94, "maxid"=>224, "name"=>"Manline Mega - Fleet 2", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>95, "maxid"=>225, "name"=>"Manline Mega - Fleet 3", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>96, "maxid"=>270, "name"=>"Manline Mega - Own", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>97, "maxid"=>96, "name"=>"Illovo Refined-Noodesburg", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>98, "maxid"=>97, "name"=>"Illovo - Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>99, "maxid"=>275, "name"=>"FMCG Commercial JHB", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>100, "maxid"=>276, "name"=>"Environmental", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>101, "maxid"=>277, "name"=>"Pen Bev", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>102, "maxid"=>282, "name"=>"Agriculture", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>103, "maxid"=>283, "name"=>"Mining", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>104, "maxid"=>284, "name"=>"Dedicated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>105, "maxid"=>337, "name"=>"T24 - Sappi", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>106, "maxid"=>338, "name"=>"Timber24", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>119, 'maxid'=>301, "name"=>"Dedicated (own)", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array('id'=>109, "maxid"=>144, "name"=>"Freight Subcontractors", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array('id'=>111, "maxid"=>296, "name"=>"Energy Subcontractors", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array('id'=>117, 'maxid'=>306, 'name'=>'Freight', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0),
                array('id'=>118, 'maxid'=>305, 'name'=>'Freight (Own)', 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0),
                array('id'=>107, 'maxid'=>9001, 'name'=>'Specialised', 'fleets'=>array(
                        array(106, 'Revenue - Timber24'), array(62, 'Manline Mega')
                        ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0),
                array('id'=>108, 'maxid'=>9002, 'name'=>'Barloworld Transport', 'fleets'=>array(
                		array(106, 'Timber24'), array(62, 'Manline Mega'), array(104, 'Dedicated'), array(33, 'Energy (Own)'), 
				array(34, 'Freight SA (Own)'), array(35, 'Freight Africa'), array(109, 'Freight Subcontractors'), 
				array(111, 'Energy Subcontractors')
                        ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0),

		array('id'=>110, 'maxid'=>9001, 'name'=>'Specialised (Own)', 'fleets'=>array(
                        array(106, 'Revenue - Timber24'), array(96, 'Manline Mega - Own')
                        ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0),

                array('id'=>112, 'maxid'=>9003, 'name'=>'Barloworld Transport (Own)', 'fleets'=>array(
               			array(106, 'Timber24'), array(96, 'Manline Mega - Own'), array(119, 'Dedicated (own)'), array(33, 'Energy (own)'),
				array(34, 'Freight SA (Own)'), array(35, 'Freight Africa')
                        ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0),
                array('id'=>113, "maxid"=>297, "name"=>"Dedicated Subcontractors", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                array('id'=>114, "maxid"=>298, "name"=>"Specialised Subcontractors", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array('id'=>115, 'maxid'=>9003, 'name'=>'Subcontractors Consolidated', 'fleets'=>array(
                        array(109, 'Freight Subcontractors'),array(111, 'Energy Subcontractors'),array(113, 'Dedicated Subcontractors'),array(114, 'Specialised Subcontractors')
                        ), 'budget'=>0, 'budkms'=>0, 'pubhol'=>1, 'display'=>1, 'displayblackouts'=>0, 'displayrasta'=>0),
		array("id"=>116, 'maxid'=>299, "name"=>"Energy", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>120, 'maxid'=>302, "name"=>"Energy Fuel and Gas", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>121, 'maxid'=>303, "name"=>"Energy Chemicals", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>122, 'maxid'=>304, "name"=>"Freight SA", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>123, 'maxid'=>281, "name"=>"Tongaat Hulletts", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>124, 'maxid'=>308, "name"=>"Mining - Adhoc", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>125, 'maxid'=>339, "name"=>"T24 - Revenue - Hfts AdHoc", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>126, 'maxid'=>309, "name"=>"RE - Rental", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>127, 'maxid'=>280, "name"=>"Warehousing Dedicated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>128, 'maxid'=>234, "name"=>"Warehousing Freight", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>129, 'maxid'=>300, "name"=>"Early Bird", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>131, 'maxid'=>271, "name"=>"Energy - NCP Cato", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>132, 'maxid'=>311, "name"=>"Energy - LPG Adhoc", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>133, 'maxid'=>312, "name"=>"Energy - LPG Tankers Consolidated", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array('id'=>134, "maxid"=>340, "name"=>"T24 - Howick Adhoc", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>135, 'maxid'=>264, "name"=>"Manline Mega Subcontractors", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>136, 'maxid'=>221, "name"=>"Freight - FMCG", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>137, 'maxid'=>122, "name"=>"Africa - General Freight", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>138, 'maxid'=>413, "name"=>"Freight - Volumax Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>139, 'maxid'=>420, "name"=>"Africa - Zambia Fleet", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>140, 'maxid'=>421, "name"=>"Africa - Tautliners", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>141, 'maxid'=>424, "name"=>"T24 - Revenue Cane", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>142, 'maxid'=>425, "name"=>"T24 - Revenue MPU Adhoc", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
		array("id"=>143, 'maxid'=>405, "name"=>"Kumkani", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                /*	array("id"=>, 'maxid'=>, "name"=>"", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0),
                        array("id"=>, 'maxid'=>, "name"=>"", "budget"=>0, "budkms"=>0, "pubhol"=>1, "display"=>1, "displayblackouts"=>0, "displayrasta"=>0), */
                );
        
        protected $_apiurl = "https://login.max.bwtsgroup.com/api_request/Report/export?";
        protected $_day = 0;
        protected $_date = 0;
        
        // Getters, or functions which return protected variables or details from them {
        public function getIncomeFleets() {
                return $this->_incomefleets;
        }
        
        public function getFleetId($index) {
                return $this->_incomefleets[$index]["id"];
        }
        // }
        
        // Standard day functions {
        public function pullFleetDay($day, $range=0) {
                $this->_day		= $day;
                $this->_date	= mktime(0, 0, 0, date("m"), $day, date("Y"));
                
                // Create date strings for query {
                $startmonth		= date("m");
                $startyear		= date("Y");
                $startday			= date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
                
                $startstring	= $startyear."-".$startmonth."-".$startday;
                //$startstring	= $startyear."-".$startmonth."-".$startday." 00:00";
                
                $stopdate		= mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
                $stopday		= date("d", $stopdate);
                $stopmonth	= date("m", $stopdate);
                $stopyear		= date("Y", $stopdate);
                
                $stopstring	= $stopyear."-".$stopmonth."-".$stopday;
                //$stopstring	= $startyear."-".$startmonth."-".$startday." 23:59";
                
                print($startstring." to ".$stopstring.PHP_EOL);
                // }
                
                // Go through each Income Fleet, checking various details and getting the trips for the day {
                foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
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
                                {
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
                                print($tripurl.PHP_EOL);
                                
                                $fileParser = new FileParser($tripurl);
                                
                                $tripdata = $fileParser->parseFile();
                                
                                if ($tripdata === false) {
                                        print("<pre style='font-family:verdana;font-size:13'>");
                                        print_r($fileParser->getErrors());
                                        print("</pre>");
                                        return;
                                        
                                        print("<pre style='font-family:verdana;font-size:13'>errors");
                                        print_r($fileParser->getErrors());
                                        print("</pre>");
                                        
                                        print("<br>");
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
                                        $triptime	= "";
                                        if(($tripval["Loading Arrival"] == "(none)") || ($tripval["Loading Arrival"] == NULL)) {
                                                if (($tripval["Loading ETA"] == "(none)") || ($tripval["Loading ETA"] == NULL))
                                                {
                                                        $triptime = $tripval["Loading Started"];
                                                }
                                                else
                                                {
                                                        $triptime	= $tripval["Loading ETA"];
                                                }
                                        } else {
                                                $triptime	= $tripval["Loading Arrival"];
                                        }
                                        
                                        $cutofftime	= $stopyear."-".$stopmonth."-".$stopday." 00:00:00";
                                        //$cutofftime	= $startyear."-".$startmonth."-".$startday." 22:00:00";
                                        
                                        if($triptime != $cutofftime) {
                                                $dayincome	+= array_key_exists("Tripleg Income", $tripval) ? str_replace(",", "", $tripval["Tripleg Income"]) : 0;
                                                //$daycontrib	+= str_replace(",", "", $tripval["Tripleg Contrib"]);
                                                # $daykms			+= array_key_exists("Total Kms", $tripval) ? $tripval["Total Kms"] : 0;
						if ($tripval["Total Kms"] > 7000)
                                                {
                                                	$daykms	+= array_key_exists("Expected Total Kms", $tripval) ? $tripval["Expected Total Kms"] : 0;
                                                }
                                                else
                                                {
                                                	$daykms	+= array_key_exists("Total Kms", $tripval) ? $tripval["Total Kms"] : 0;
                                                }
                                                if (array_key_exists('Subcontractor', $tripval) && $tripval['Subcontractor'] && ($tripval['Subcontractor'] !== '(none)'))
                                                {
                                                        $subbie_income	+= array_key_exists("Tripleg Income", $tripval) ? str_replace(",", "", $tripval["Tripleg Income"]) : 0;
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
                $this->_day		= $day;
                $this->_date	= mktime(0, 0, 0, date("m"), $day, date("Y"));
                
                // Create date strings for query {
                $startmonth		= date("m");
                $startyear		= date("Y");
                $startday			= date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
                
                $startstring	= $startyear."-".$startmonth."-".$startday;
                //$startstring	= $startyear."-".$startmonth."-".$startday." 00:00";
                
                $stopdate		= mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
                $stopday		= date("d", $stopdate);
                $stopmonth	= date("m", $stopdate);
                $stopyear		= date("Y", $stopdate);
                
                $stopstring	= $stopyear."-".$stopmonth."-".$stopday;
                //$stopstring	= $startyear."-".$startmonth."-".$startday." 23:59";
                
                print($startstring." to ".$stopstring.PHP_EOL);
                // }
                
                // Go through each Income Fleet, checking various details and getting the trips for the day {
                foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
                        if (array_key_exists('fleets', $incfleetval))
                        {
                                $fleetscore[$incfleetval["id"]]["fleetid"] = $incfleetval["id"];
                                $fleetscore[$incfleetval["id"]]["contrib"] = (float)0;
                                $fleetscore[$incfleetval["id"]]["day"] = $this->_day;
                                $fleetscore[$incfleetval["id"]]["date"] = $this->_date;
                                $fleetscore[$incfleetval["id"]]["contribupdated"]	= date("U");
                                foreach ($incfleetval['fleets'] as $key=>$val)
                                {
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
                                
                                $fileParser = new FileParser($tripurl);
                                
                                $tripdata = $fileParser->parseFile();
                                
                                if ($tripdata === false) {
                                        print("<pre style='font-family:verdana;font-size:13'>");
                                        print_r($fileParser->getErrors());
                                        print("</pre>");
                                        return;
                                        
                                        print("<pre style='font-family:verdana;font-size:13'>errors");
                                        print_r($fileParser->getErrors());
                                        print("</pre>");
                                        
                                        print("<br>");
                                }
                                // }
                                
                                foreach ($tripdata as $tripkey=>$tripval) {
                                        $triptime	= "";
                                        if((array_key_exists("Loading Arrival", $tripval)) && (isset($tripval["Loading Arrival"])) && ($tripval["Loading Arrival"] == "(none)") || ($tripval["Loading Arrival"] == null)) {
                                                $triptime	= isset($tripval["Loading ETA"]) ? $tripval["Loading ETA"] : 2/24;
                                        } else {
                                                $triptime	= isset($tripval["Loading Arrival"]) ? $tripval["Loading Arrival"] : 2/24;
                                        }
                                        
                                        $cutofftime	= $stopyear."-".$stopmonth."-".$stopday." 00:00:00";
                                        //$cutofftime	= $startyear."-".$startmonth."-".$startday." 22:00:00";
                                        
                                        if($triptime != $cutofftime) {
                                                if (isset($tripval["Tripleg Contrib"])) {
                                                        $daycontrib	+= str_replace(",", "", $tripval["Tripleg Contrib"]);
                                                }
                                                if (isset($tripval["Total Kms"])) {
                                                        $daykms			+= $tripval["Total Kms"];
                                                }
                                        }
                                }
                                
                                $fleetscore[$incfleetval["id"]]["fleetid"]				= $incfleetval["id"];
                                $fleetscore[$incfleetval["id"]]["contrib"]				= $daycontrib;
                                $fleetscore[$incfleetval["id"]]["day"]						= $this->_day;
                                $fleetscore[$incfleetval["id"]]["date"]						= $this->_date;
                                $fleetscore[$incfleetval["id"]]["contribupdated"]	= date("U");
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
                foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
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
                                foreach ($incfleetval['fleets'] as $key=>$val)
                                {
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
                                $fileParser = new FileParser($budgeturl);
                                $fileParser->setCurlFile("budget.".$incfleetval["id"].".csv");
                                $budgetdata = $fileParser->parseFile();
                                if ($budgetdata === false) {
                                        print("<pre style='font-family:verdana;font-size:13'>");
                                        print_r($fileParser->getErrors());
                                        print("</pre>");
                                        return;
                                        print("<pre style='font-family:verdana;font-size:13'>errors");
                                        print_r($fileParser->getErrors());
                                        print("</pre>");
                                        print("<br />");
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
        /** fleetdayHandler::importBudget($fleet = NULL)
        * Import this months budget data
        * @author Feighen Oosterbroek
        * @author feighen@manlinegroup.com
	* @param $fleet INT Which do we want import?
        * @return FALSE on failure NULL otherwise
        */
        public function importBudget($fleet = NULL) {
                for ($i=1;$i<=date("t");$i++) {
                        foreach ($this->_incomefleets as $incfleetkey=>$incfleetval) {
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
                                        foreach ($incfleetval['fleets'] as $key=>$val)
                                        {
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
                                        $fileParser = new FileParser($budgeturl);
                                        $fileParser->setCurlFile("budget".$incfleetval["id"].date('d', strtotime($startDate)).".csv");
                                        $budgetdata = $fileParser->parseFile();
                                        if ($budgetdata === false) {
                                                print("<pre style='font-family:verdana;font-size:13'>");
                                                print_r($fileParser->getErrors());
                                                print("</pre>");
                                                return;
                                                print("<pre style='font-family:verdana;font-size:13'>errors");
                                                print_r($fileParser->getErrors());
                                                print("</pre>");
                                                print("<br />");
                                        }
                                        //: End
                                        //: Collate data
                                        foreach ($budgetdata as $budgetkey=>$budgetval) {
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
                                                
                                                $truckbudgetcontrib	= isset($budgetval["Contribution"]) ? str_replace(",", "", $budgetval["Contribution"]) : "";
                                                $truckbudgetcontrib	= str_replace("R", "", $truckbudgetcontrib);
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
                        /* //: Testing
                        if ($i > 1) {
                        break;
                        }
                        //: End */
                }
        }
        //: End
        
        //: T24 Functions
        public function getBudgetsInDateRange($start, $end, $trucks)
        {
                $sql = (string)"SELECT `date`, SUM(`kms`), SUM(`income`)/100, SUM(`contribution`)/100 FROM `application_3`.`udo_truckbudget` WHERE `date`>='".$start."' AND date<'".$end."' AND `truck_id` IN (".$trucks.")";
                // print($sql);
                //: Establish a link to the data of Max
                $mysqli = new mysqli("192.168.1.19", "root", "kaluma", "application_3");
                //: End
                if ($result = $mysqli->query($sql))
                {
                        $monthBudget = (array)array();
                        while($obj = $result->fetch_array()){ 
                                foreach ($obj as $key=>$val) {
                                        if (is_int($key) === TRUE)
                                        {
                                                unset($obj[$key]);
                                        }
                                }
                                $monthBudget[$obj["date"]][] = $obj;
                        }
                        
                        /* free result set */
                        $result->close();
                }
                
                //: Close link to Max db
                $mysqli->close();
                return $monthBudget;
        }
        
        public function getIdFromFleetnum($fleetnum)
        {
                $sql = (string)"SELECT * FROM `application_3`.`udo_truck` WHERE `fleetnum`='".$fleetnum."'";
                //print($sql);
                //: Establish a link to the data of Max
                $mysqli = new mysqli("192.168.1.19", "root", "kaluma", "application_3");
                //: End
                if ($result = $mysqli->query($sql))
                {
                        $truckData = (array)array();
                        while($obj = $result->fetch_array()){ 
                                foreach ($obj as $key=>$val) {
                                        if (is_int($key) === TRUE)
                                        {
                                                unset($obj[$key]);
                                        }
                                }
                                $truckData[] = $obj;
                        }
                        
                        /* free result set */
                        $result->close();
                }
                
                //: Close link to Max db
                $mysqli->close();
                return $truckData;	
        }
        
        public function pullT24FleetData($day, $range=0)
        {
                $this->_day	= $day;
                $this->_date	= mktime(0, 0, 0, date("m"), $day, date("Y"));
                
                //: Create date strings for query
                $startmonth	= date("m");
                $startyear	= date("Y");
                $startday	= date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
                $startstring	= $startyear."-".$startmonth."-".$startday;
                $stopdate	= mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
                $stopday	= date("d", $stopdate);
                $stopmonth	= date("m", $stopdate);
                $stopyear	= date("Y", $stopdate);
                $stopstring	= $stopyear."-".$stopmonth."-".$stopday;
                
                print($startstring." to ".$stopstring.PHP_EOL);
                //: End
                $trucksinafleet = (array)array();
                foreach ($this->_incomefleets as $key=>$val)
                {
                        //: Don't need to loop through Max Fleets
                        if (array_key_exists("t24", $val) === FALSE)
                        {
                                continue;
                        }
                        $truckurl = preg_replace("/login/", "t24", $this->_apiurl)."report=73&responseFormat=csv&Start_Date=".$startstring."&Stop_Date=".$stopstring."&Fleet=".$val["maxid"];
                        print($truckurl.PHP_EOL);
                        $fileParser = new FileParser($truckurl);
                        $fileParser->setCurlFile("trucks".$val["id"].".csv");
                        $truckdata = $fileParser->parseFile();
                        //: Populate the truck_id
                        foreach ($truckdata as $tdkey=>$tdval)
                        {
                                $id = $this->getIdFromFleetnum($tdval["Truck"]);
                                if ((array_key_exists(0, $id) === FALSE) && (array_key_exists("ID", $id[0]) === FALSE))
                                {
                                        //: something went wrong with getting the truck data from Max --- Hells bells
                                        continue;
                                }
                                $tdval["truck_id"] = $id[0]["ID"];
                                $tdval["fleet_id"] = $val["id"];
                                $trucksinafleet[] = $tdval;
                        }
                }
                return $trucksinafleet;
        }
        
        public function pullFleetDayT24($day, $range=0)
        {
                $this->_day = $day;
                $this->_date = mktime(0, 0, 0, date("m"), $day, date("Y"));
                //: Create date strings for query
                $startmonth = date("m");
                $startyear = date("Y");
                $startday = date("d", mktime(0, 0, 0, $startmonth, ($this->_day - $range), $startyear));
                
                $startstring = $startyear."-".$startmonth."-".$startday;
                
                $stopdate = mktime(0, 0, 0, $startmonth, ($this->_day + 1), $startyear);
                $stopday = date("d", $stopdate);
                $stopmonth = date("m", $stopdate);
                $stopyear = date("Y", $stopdate);
                
                $stopstring = $stopyear."-".$stopmonth."-".$stopday;
                
                print($startstring." to ".$stopstring.PHP_EOL);
                //: End
                //: Content
                /** To DO
                * 1) Get all income for trucks
                * 2) loop through fleets to get list of trucks attached to each fleet
                * 3) sum data for each fleet
                * 4) save to maxinedb.fleet_scores
                */
                $income = preg_replace("/login/", "t24", $this->_apiurl)."report=79&responseFormat=csv&Date%20Start=".$startstring."&Date%20Stop=".$stopstring;
                print($income.PHP_EOL);
                $fileParser = new FileParser($income);
                $fileParser->setCurlFile("income".$startstring.".csv");
                $data = $fileParser->parseFile();
                // print_r($data);
                if (is_array($data) === FALSE)
                {
                        //: 404
                        return FALSE;
                }
                $trucksinafleet = $this->pullT24FleetData($startday);
                $fleetTrucks = (array)array();
                foreach ($trucksinafleet as $truck)
                {
                        $fleetTrucks[$truck["fleet_id"]][] = $truck;
                }
                $inc = (array)array();
                foreach ($data as $row)
                {
                        $inc[$row["Truck"]][] = $row;
                }
                $fleets = (array)array();
                foreach ($fleetTrucks as $key=>$row)
                {
                        $t24Income = (array)array();
                        $t24Income["fleetid"] = $key;
                        $t24Income["day"] = $this->_day;
                        $t24Income["date"] = $this->_date;
                        $t24Income["updated"] = date("U");
                        $t24Income["income"] = (float)0;
                        $t24Income["kms"] = (float)0;
                        foreach ($row as $val)
                        {
                                if (array_key_exists($val["Truck"], $inc))
                                {
                                        foreach ($inc[$val["Truck"]] as $trinc)
                                        {
                                                if (preg_match('/error\:/', $trinc["Tripleg Income"]))
                                        	{
                                                	$t24Income["income"] += (float)0.00;
                                        	}
                                        	else
                                        	{
                                        		$t24Income["income"] += (float)preg_replace("/\,{0,}/", "", $trinc["Tripleg Income"]);
                                        	}
                                                if (array_key_exists('Lead Kms', $trinc) && ($trinc['Lead Kms'] != '(none)'))
                                                {
                                                	$t24Income['kms'] += (floatval($trinc['Lead Kms'])*2);
                                                }
                                                else
                                                {
                                                	$t24Income['kms'] += (floatval($trinc['Kms in Trip leg'])+floatval($trinc['Empty Kms']));
                                                }
                                        }
                                }
                        }
                        $fleets[$key] = $t24Income;
                }
                return $fleets;
        }

	public function findT24BackDay($today) {
                $file = (string)'t24';
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
        //: End
        
        public function saveFleetDay($fleetscore) {
                // Create or commit records to database {
                $record	= sqlPull(array("table"=>"fleet_scores", "where"=>"date=".$this->_date, "customkey"=>"fleetid"));
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
                $date			= mktime(0,0,0,date("m"), date("d"), date("Y"));
                
                $fleetday	= sqlPull(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleet." AND date=".$date, "sort"=>"day", "customkey"=>"day"));
                
                //$fleetday	= $this->useArtificialBudgets($fleet, $fleetday);
                
                return $fleetday;
        }
        
        public function getFleetScoreMonth($fleet) {
                $startdate	= mktime(0, 0, 0, date("m"), 1, date("Y"));
                $enddate		= mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                /*
                print("<div style='color:WHITE;'>");
                print("Start Date: ".date("d m Y", $startdate)." ".$startdate."<br>");
                print("End Date ".date("d m Y", $enddate)." ".$enddate."<br>");
                print("</div>");
                */
                
                $day				= date("d");
                
                $fleetdays	= sqlPull(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleet." AND date>=".$startdate." AND date<=".$enddate, "sort"=>"day", "customkey"=>"day"));
                
                //$fleetdays	= $this->useArtificialBudgets($fleet, $fleetdays);
                
                return $fleetdays;
        }
        
        public function calcSliderTop($budget) {
                $slidertop	= 0;
                $increment	= 5000;
                $margin			= $budget + $increment / 5;
                while($slidertop < $margin) {
                        if($slidertop > ($increment * 10)) {
                                $increment *= 2;
                        }
                        
                        $slidertop	+= $increment;
                }
                return $slidertop;
        }
        
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
        
        private function useArtificialBudgets($fleet, $fleetdays) {
                $pubholidays	= array(41=>41);
                
                $month				= date("m");
                $year					= date("Y");
                $fleetnumber	= 0;
                foreach ($this->_incomefleets as $fleetkey=>$fleetval) {
                        if($fleetval["id"] == $fleet) {
                                $fleetnumber	= $fleetkey;
                        }
                }
                
                foreach ($fleetdays as $daykey=>$dayval) {
                        $day	= $dayval["day"];
                        
                        $weekday		= date("w", mktime(0,0,1,$month,$day,$year));
                        
                        $daybudget	= 0;
                        $daybudget	= $this->_incomefleets[$fleetnumber]["budget"];
                        
                        
                        if(($this->_incomefleets[$fleetnumber]["pubhol"] == 1) && ($pubholidays[$day])) {
                                $daybudget	= 0; // This fleet is not budgetted to make income on Public holidays
                        } else if(($weekday == 6) || ($weekday == 0)) {
                                $daybudget	= ($daybudget / 2);
                        }
                        
                        $fleetdays[$daykey]["budget"]	= $daybudget;
                        $fleetdays[$daykey]["budkms"]	= $this->_incomefleets[$fleetnumber]["budkms"];
                }
                
                return $fleetdays;
        }
        //: End
} // This is the end of the class.  Do not put class functions after it.
?>