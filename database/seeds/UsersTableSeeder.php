<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nonghyups = [
            // 관리자(2)
            [ 'nonghyup_id'=>'cnadmin', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'충남도청' ],
            [ 'nonghyup_id'=>'nhadmin', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'충남농협' ],
            // 시군담당자(15)
            [ 'nonghyup_id'=>'cnadmin01', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'천안시' ],
            [ 'nonghyup_id'=>'cnadmin02', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'공주시' ],
            [ 'nonghyup_id'=>'cnadmin03', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'보령시' ],
            [ 'nonghyup_id'=>'cnadmin04', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'아산시' ],
            [ 'nonghyup_id'=>'cnadmin05', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'서산시' ],
            [ 'nonghyup_id'=>'cnadmin06', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'논산시' ],
            [ 'nonghyup_id'=>'cnadmin07', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'당진시' ],
            // [ 'nonghyup_id'=>'cnadmin08', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=> 1, 'sequence'=>0, 'name'=>'세종시' ],
            [ 'nonghyup_id'=>'cnadmin09', 'sigun_code'=>'gs', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'금산군' ],
            [ 'nonghyup_id'=>'cnadmin10', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'부여군' ],
            [ 'nonghyup_id'=>'cnadmin11', 'sigun_code'=>'sc', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'서천군' ],
            [ 'nonghyup_id'=>'cnadmin12', 'sigun_code'=>'cy', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'청양군' ],
            [ 'nonghyup_id'=>'cnadmin13', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'홍성군' ],
            [ 'nonghyup_id'=>'cnadmin14', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'예산군' ],
            [ 'nonghyup_id'=>'cnadmin15', 'sigun_code'=>'ta', 'activated'=>1, 'is_admin'=>1, 'is_input_allowed'=>1, 'sequence'=>0, 'name'=>'태안군' ],
            // // 천안시(7)
            [ 'nonghyup_id'=>'nh485014', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'천안농협' ],
            [ 'nonghyup_id'=>'nh485047', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'동천안농협' ],
            [ 'nonghyup_id'=>'nh485058', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'성거농협' ],
            [ 'nonghyup_id'=>'nh485069', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'성환농협' ],
            [ 'nonghyup_id'=>'nh485070', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'입장농협' ],
            [ 'nonghyup_id'=>'nh485081', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'직산농협' ],
            [ 'nonghyup_id'=>'nh485092', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'아우내농협' ],
            // // 공주시(11)
            [ 'nonghyup_id'=>'nh457017', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'계룡농협' ],
            [ 'nonghyup_id'=>'nh457039', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'탄천농협' ],
            [ 'nonghyup_id'=>'nh457040', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'유구농협' ],
            [ 'nonghyup_id'=>'nh457051', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'정안농협' ],
            [ 'nonghyup_id'=>'nh457062', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'의당농협' ],
            [ 'nonghyup_id'=>'nh457073', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'신풍농협' ],
            [ 'nonghyup_id'=>'nh457084', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'반포농협' ],
            [ 'nonghyup_id'=>'nh457095', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>8, 'name'=>'사곡농협' ],
            [ 'nonghyup_id'=>'nh457109', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>9, 'name'=>'우성농협' ],
            [ 'nonghyup_id'=>'nh457110', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>10, 'name'=>'이인농협' ],
            [ 'nonghyup_id'=>'nh457121', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>11, 'name'=>'공주농협' ],
            // // 보령시(7)
            [ 'nonghyup_id'=>'nh467011', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'대천농협' ],
            [ 'nonghyup_id'=>'nh467043', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'남포농협' ],
            [ 'nonghyup_id'=>'nh467054', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'오천농협' ],
            [ 'nonghyup_id'=>'nh467065', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'청소농협' ],
            [ 'nonghyup_id'=>'nh467076', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'천북농협' ],
            [ 'nonghyup_id'=>'nh467087', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'웅천농협' ],
            [ 'nonghyup_id'=>'nh467098', 'sigun_code'=>'br', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'주산농협' ],
            // // 아산시(10)
            [ 'nonghyup_id'=>'nh483012', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'온양농협' ],
            [ 'nonghyup_id'=>'nh483023', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'송악농협' ],
            [ 'nonghyup_id'=>'nh483034', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'배방농협' ],
            [ 'nonghyup_id'=>'nh483045', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'탕정농협' ],
            [ 'nonghyup_id'=>'nh483056', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'음봉농협' ],
            [ 'nonghyup_id'=>'nh483067', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'둔포농협' ],
            [ 'nonghyup_id'=>'nh483078', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'영인농협' ],
            [ 'nonghyup_id'=>'nh483089', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>8, 'name'=>'인주농협' ],
            [ 'nonghyup_id'=>'nh483115', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>9, 'name'=>'선도농협' ],
            [ 'nonghyup_id'=>'nh483126', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>10, 'name'=>'염치농협' ],
            // // 서산시(9)
            [ 'nonghyup_id'=>'nh477013', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'서산농협' ],
            [ 'nonghyup_id'=>'nh477035', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'부석농협' ],
            [ 'nonghyup_id'=>'nh477057', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'지곡농협' ],
            [ 'nonghyup_id'=>'nh477068', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'대산농협' ],
            [ 'nonghyup_id'=>'nh477079', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'성연농협' ],
            [ 'nonghyup_id'=>'nh477080', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'음암농협' ],
            [ 'nonghyup_id'=>'nh477091', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'운산농협' ],
            [ 'nonghyup_id'=>'nh477105', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>8, 'name'=>'해미농협' ],
            [ 'nonghyup_id'=>'nh477116', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>9, 'name'=>'고북농협' ],
            // // 논산시(10)
            [ 'nonghyup_id'=>'nh461014', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'논산농협' ],
            [ 'nonghyup_id'=>'nh461025', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'부적농협' ],
            [ 'nonghyup_id'=>'nh461036', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'광석농협' ],
            [ 'nonghyup_id'=>'nh461047', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'노성농협' ],
            [ 'nonghyup_id'=>'nh461058', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'상월농협' ],
            [ 'nonghyup_id'=>'nh461070', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'논산계룡농협' ],
            [ 'nonghyup_id'=>'nh461106', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'양촌농협' ],
            [ 'nonghyup_id'=>'nh461117', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>8, 'name'=>'강경농협' ],
            [ 'nonghyup_id'=>'nh461128', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>9, 'name'=>'성동농협' ],
            [ 'nonghyup_id'=>'nh461140', 'sigun_code'=>'ns', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>10, 'name'=>'연무농협' ],
            // // 당진시(12)
            [ 'nonghyup_id'=>'nh481010', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'당진농협' ],
            [ 'nonghyup_id'=>'nh481021', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'고대농협' ],
            [ 'nonghyup_id'=>'nh481032', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'석문농협' ],
            [ 'nonghyup_id'=>'nh481043', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'대호지농협' ],
            [ 'nonghyup_id'=>'nh481054', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'정미농협' ],
            [ 'nonghyup_id'=>'nh481065', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'면천농협' ],
            [ 'nonghyup_id'=>'nh481076', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'순성농협' ],
            [ 'nonghyup_id'=>'nh481087', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>8, 'name'=>'합덕농협' ],
            [ 'nonghyup_id'=>'nh481098', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>9, 'name'=>'우강농협' ],
            [ 'nonghyup_id'=>'nh481102', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>10, 'name'=>'신평농협' ],
            [ 'nonghyup_id'=>'nh481113', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>11, 'name'=>'송악농협' ],
            [ 'nonghyup_id'=>'nh481124', 'sigun_code'=>'dj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>12, 'name'=>'송산농협' ],
            // // 세종시(8)
            [ 'nonghyup_id'=>'nh455015', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'남세종농협' ],
            [ 'nonghyup_id'=>'nh455026', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'세종전의농협' ],
            [ 'nonghyup_id'=>'nh455037', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'세종중앙농협' ],
            [ 'nonghyup_id'=>'nh455041', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'조치원농협' ],
            [ 'nonghyup_id'=>'nh455059', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'동세종농협' ],
            [ 'nonghyup_id'=>'nh455071', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'서세종농협' ],
            [ 'nonghyup_id'=>'nh455028', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'세종서부농협' ],
            [ 'nonghyup_id'=>'nh455072', 'sigun_code'=>'sj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>8, 'name'=>'세종동부농협' ],
            // // 금산군(4)
            [ 'nonghyup_id'=>'nh451011', 'sigun_code'=>'gs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'금산농협' ],
            [ 'nonghyup_id'=>'nh451055', 'sigun_code'=>'gs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'부리농협' ],
            [ 'nonghyup_id'=>'nh451088', 'sigun_code'=>'gs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'만인산농협' ],
            [ 'nonghyup_id'=>'nh451099', 'sigun_code'=>'gs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'진산농협' ],
            // // 부여군(7)
            [ 'nonghyup_id'=>'nh463016', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'세도농협' ],
            [ 'nonghyup_id'=>'nh463038', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'장암농협' ],
            [ 'nonghyup_id'=>'nh463094', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'규암농협' ],
            [ 'nonghyup_id'=>'nh463119', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'구룡농협' ],
            [ 'nonghyup_id'=>'nh463131', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'서부여농협' ],
            [ 'nonghyup_id'=>'nh463142', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'부여농협' ],
            [ 'nonghyup_id'=>'nh463164', 'sigun_code'=>'by', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'동부여농협' ],
            // // 서천군(6)
            [ 'nonghyup_id'=>'nh465018', 'sigun_code'=>'sc', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'서천농협' ],
            [ 'nonghyup_id'=>'nh465041', 'sigun_code'=>'sc', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'판교농협' ],
            [ 'nonghyup_id'=>'nh465063', 'sigun_code'=>'sc', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'서서천농협' ],
            [ 'nonghyup_id'=>'nh465085', 'sigun_code'=>'sc', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'한산농협' ],
            [ 'nonghyup_id'=>'nh465100', 'sigun_code'=>'sc', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'동서천농협' ],
            [ 'nonghyup_id'=>'nh465122', 'sigun_code'=>'sc', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'장항농협' ],
            // // 청양군(3)
            [ 'nonghyup_id'=>'nh471017', 'sigun_code'=>'cy', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'청양농협' ],
            [ 'nonghyup_id'=>'nh471040', 'sigun_code'=>'cy', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'정산농협' ],
            [ 'nonghyup_id'=>'nh471095', 'sigun_code'=>'cy', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'화성농협' ],
            // // 홍성군(10)
            [ 'nonghyup_id'=>'nh473019', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'홍성농협' ],
            [ 'nonghyup_id'=>'nh473020', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'홍북농협' ],
            [ 'nonghyup_id'=>'nh473031', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'금마농협' ],
            [ 'nonghyup_id'=>'nh473042', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'홍동농협' ],
            [ 'nonghyup_id'=>'nh473053', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'구항농협' ],
            [ 'nonghyup_id'=>'nh473064', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'갈산농협' ],
            [ 'nonghyup_id'=>'nh473075', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'광천농협' ],
            [ 'nonghyup_id'=>'nh473086', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>8, 'name'=>'장곡농협' ],
            [ 'nonghyup_id'=>'nh473101', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>9, 'name'=>'결성농협' ],
            [ 'nonghyup_id'=>'nh473112', 'sigun_code'=>'hs', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>10, 'name'=>'서부농협' ],
            // // 예산군(7)
            [ 'nonghyup_id'=>'nh475011', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'신양농협' ],
            [ 'nonghyup_id'=>'nh475022', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'예산중앙농협' ],
            [ 'nonghyup_id'=>'nh475066', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'광시농협' ],
            [ 'nonghyup_id'=>'nh475077', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'삽교농협' ],
            [ 'nonghyup_id'=>'nh475088', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'덕산농협' ],
            [ 'nonghyup_id'=>'nh475103', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'고덕농협' ],
            [ 'nonghyup_id'=>'nh475125', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>7, 'name'=>'예산농협' ],
            // // 태안군(6)
            [ 'nonghyup_id'=>'nh477127', 'sigun_code'=>'ta', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>1, 'name'=>'안면도농협' ],
            [ 'nonghyup_id'=>'nh477138', 'sigun_code'=>'ta', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>2, 'name'=>'남면농협' ],
            [ 'nonghyup_id'=>'nh477149', 'sigun_code'=>'ta', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>3, 'name'=>'태안농협' ],
            [ 'nonghyup_id'=>'nh477150', 'sigun_code'=>'ta', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>4, 'name'=>'근흥농협' ],
            [ 'nonghyup_id'=>'nh477161', 'sigun_code'=>'ta', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>5, 'name'=>'소원농협' ],
            [ 'nonghyup_id'=>'nh477172', 'sigun_code'=>'ta', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>6, 'name'=>'원북농협' ],
            // // 품농(5)
            [ 'nonghyup_id'=>'nh457811', 'sigun_code'=>'gj', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>99, 'name'=>'세종공주원예농협' ],
            [ 'nonghyup_id'=>'nh475815', 'sigun_code'=>'ys', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>99, 'name'=>'예산능금농협' ],
            [ 'nonghyup_id'=>'nh477817', 'sigun_code'=>'ss', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>99, 'name'=>'충서원예농협' ],
            [ 'nonghyup_id'=>'nh483816', 'sigun_code'=>'as', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>99, 'name'=>'아산원예농협' ],
            [ 'nonghyup_id'=>'nh485818', 'sigun_code'=>'ca', 'activated'=>1, 'is_admin'=>0, 'is_input_allowed'=>1, 'sequence'=>99, 'name'=>'천안배농협' ],
            // // 논산계룡농협 계룡지점 : 지점은 사업주체가 될 수 없으니 제외
            // // [ 'nonghyup_id'=>'', 'sigun_code'=>'', 'activated'=>1, 'is_admin'=>0, 'sequence'=>5, 'name'=>'논산계룡농협 계룡지점' ],
        ];


        foreach ($nonghyups as $nonghyup) {
            App\User::create([
              'nonghyup_id'     => $nonghyup['nonghyup_id'],
              'password'        => bcrypt('secret1@'),
              'sigun_code'      => $nonghyup['sigun_code'],
              'name'            => $nonghyup['name'],
              // 'address'         => $nonghyup['address'],
              // 'contact'         => $nonghyup['contact'],
              // 'representative'  => $nonghyup['representative'],
              'activated'       => $nonghyup['activated'],
              'is_admin'        => $nonghyup['is_admin'],
              'is_input_allowed'=> $nonghyup['is_input_allowed'],
              'sequence'        => $nonghyup['sequence'],
            ]);
        }

        // DB::table('users')->insert([
        //     'user_id'         => 'onthesys',
        //     'sigun_code'      => 'ca',
        //     'name'            => 'ONTHESYS',
        //     'address'         => '187 418',
        //     'contact'         => '0424842013',
        //     'representative'  => '오진덕',
        //     'activated'       => 1,
        //     'is_admin'        => 1,
        //     'sequence'        => 1,
        //     'password'        => bcrypt('admin123!'),
        //     'created_at'      => now(),
        //     'updated_at'      => now(),
        //     // 'remember_token'  => Str::random(10),
        // ]);

        // factory(App\User::class, 10)->create();
    }
}
