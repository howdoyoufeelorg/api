<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 03/04/2020
 * Time: 2:51 pm
 */

namespace App\DataFixtures;


use App\Entity\Area;
use App\Entity\Country;
use App\Entity\State;
use App\Entity\ZipcodePartial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class UsGeoEntitiesFixtures extends Fixture
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function load(ObjectManager $manager)
    {
        $usStateData = [
            'AL'=>["Alabama", "35-36"],
            'AK'=>["Alaska","995-999"],
            'AZ'=>["Arizona","85-86"],
            'AR'=>["Arkansas","716-729"],
            'CA'=>["California","900-961"],
            'CO'=>["Colorado","80-81"],
            'CT'=>["Connecticut","06"],
            'DE'=>["Delaware","197-199"],
            'DC'=>["District Of Columbia","200-205"],
            'FL'=>["Florida","32-34"],
            'GA'=>["Georgia","30-31"],
            'HI'=>["Hawaii","967-968"],
            'ID'=>["Idaho","832-839"],
            'IL'=>["Illinois","60-62"],
            'IN'=>["Indiana","46-47"],
            'IA'=>["Iowa","50-52"],
            'KS'=>["Kansas","66-67"],
            'KY'=>["Kentucky","40-42"],
            'LA'=>["Louisiana","700-715"],
            'ME'=>["Maine","039-049"],
            'MD'=>["Maryland","206-219"],
            'MA'=>["Massachusetts","010-027"],
            'MI'=>["Michigan","48-49"],
            'MN'=>["Minnesota","550-567"],
            'MS'=>["Mississippi","386-399"],
            'MO'=>["Missouri","63-65"],
            'MT'=>["Montana","59"],
            'NE'=>["Nebraska","68-69"],
            'NV'=>["Nevada","889-899"],
            'NH'=>["New Hampshire","030-038"],
            'NJ'=>["New Jersey","07-08"],
            'NM'=>["New Mexico","870-884"],
            'NY'=>["New York","10-14"],
            'NC'=>["North Carolina","27-28"],
            'ND'=>["North Dakota","58"],
            'OH'=>["Ohio","43-45"],
            'OK'=>["Oklahoma","73-74"],
            'OR'=>["Oregon","97"],
            'PA'=>["Pennsylvania","150-196"],
            'RI'=>["Rhode Island","028-029"],
            'SC'=>["South Carolina","29"],
            'SD'=>["South Dakota","57"],
            'TN'=>["Tennessee","370-385"],
            'TX'=>["Texas","75-79"],
            'UT'=>["Utah","84"],
            'VT'=>["Vermont","05"],
            'VA'=>["Virginia","220-246"],
            'WA'=>["Washington","980-994"],
            'WV'=>["West Virginia","246-249"],
            'WI'=>["Wisconsin","53-54"],
            'WY'=>["Wyoming", "820-831"],
            'PuertoRico'=>["Puerto Rico", "006,007,009"],
            'VirginIslands'=>["Virgin Islands", "008"],
        ];

        $specialStateNames = [
            'AA' => ["Armed Forces America", "340"],
            'AE' => ["Armed Forces Europe", "09"],
            'AP' => ["Armed Forces Pacific", "962-966"],
            'DC' => ["District of Columbia", "200,569"],
            'NY' => ["New York", "005"],
            'FG' => ["Fed Gov", "202-205"],
            'TX' => ["???", "885"],
            'GU' => ["Guam", "969"],
            'OU' => ["Outside US", "001"],
        ];

        $country = new Country('United States');
        $manager->persist($country);

        foreach($usStateData as $stateAbbr => $data) {
            $stateName = $data[0];
            $areaRange = $data[1];
            $state = new State($stateName);
            $twitterResource = $this->getTwitterResource($stateAbbr);
            if($twitterResource) {
                $state->addTwitterResource(['description' => "$stateName official Twitter", 'value' => $twitterResource]);
            }
            $country->addState($state);

            $areaPartialsArray = $this->getPartialsArray($areaRange);
            foreach($areaPartialsArray as $item) {
                $areaName = "$stateName $item";
                $area = new Area($areaName);
                $area->addZipcodePartial(new ZipcodePartial($item));
                $state->addArea($area);
            }
        }

        $manager->flush();
    }

    private function getPartialsArray(string $input) {
        if(preg_match('/-/', $input)) {
            $result = [];
            list($start, $end) = explode('-', $input);
            if(strlen($start) != strlen($end)) {
                return [];
            }
            $targetLength = strlen($start);
            $start = (int)$start; $end = (int)$end;
            for($i = $start; $i <= $end; $i++) {
                $result[] = str_pad($i, $targetLength, "0", STR_PAD_LEFT);
            }
            return $result;
        }
        if(preg_match('/,/', $input)) {
            return explode(',',$input);
        }
        return [$input];
    }

    private function getTwitterResource($stateAbbr) {
        $twitterResources = [
            "AL" => "alpublichealth  ",
            "AK" => "Alaska_DHSS     ",
            "AZ" => "azdhs           ",
            "AR" => "adhpio          ",
            "CA" => "CAPublicHealth  ",
            "CO" => "cdphe           ",
            "CT" => "CTDPH           ",
            "DE" => "delaware_dhss   ",
            "DC" => "DMHHS_DC        ",
            "FL" => "HealthyFla      ",
            "GA" => "GaDPH           ",
            "HI" => "HIgov_Health    ",
            "ID" => "idhw            ",
            "IL" => "IDPH            ",
            "IN" => "StateHealthIN   ",
            "IA" => "IAPublicHealth  ",
            "KS" => "KDHE            ",
            "KY" => "UKCPH           ",
            "LA" => "LADeptHealth    ",
            "ME" => "MEPublicHealth  ",
            "MT" => "DPHHSMT         ",
            "NE" => "NEDHHS          ",
            "NV" => "HealtHIENevada  ",
            "NH" => "NHDHHSPIO       ",
            "NJ" => "NJGov           ",
            "NM" => "nmdoh           ",
            "NY" => "healthnygov     ",
            "NC" => "ncdhhs          ",
            "ND" => "NDDOH           ",
            "OH" => "OHdeptofhealth  ",
            "OK" => "HealthyOklahoma ",
            "OR" => "OHAOregon       ",
            "MD" => "MDHealthDept    ",
            "MA" => "massgov         ",
            "MI" => "MichiganHHS     ",
            "MN" => "mnhealth        ",
            "MS" => "msdh            ",
            "MO" => "HealthyLivingMo ",
            "PA" => "PAHealthDept    ",
            "RI" => "rihealth        ",
            "SC" => "scdhec          ",
            "SD" => "SDDOH           ",
            "TN" => "TNDeptofHealth  ",
            "TX" => "TexasDSHS       ",
            "UT" => "UtahDepOfHealth ",
            "VT" => "healthvermont   ",
            "VA" => "vdhgov          ",
            "WA" => "WADeptHealth    ",
            "WV" => "WV_DHHR         ",
            "WI" => "DHSWI           ",
            "WY" => "healthywyo      ",
        ];
        if(array_key_exists($stateAbbr, $twitterResources)) {
            return trim($twitterResources[$stateAbbr]);
        }
        return '';
    }
}