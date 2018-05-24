<?php
/**
 * Created by PhpStorm.
 * User: huchunyu
 */
namespace Admin\Controller;
use Think\Controller;
header("Content-type: text/html; charset=utf-8");


class ExportusersController extends Controller
{

    public function index()
{

    $DB_w = C("DB_URL");
    $User = M("user_users as us",'wm_',$DB_w);
    $sqlCount=$User ->join('wm_user_banks   as ub ON us.id = ub.user_id')
        // ->field("us.id ")
        ->where("ub.status = 0 and us.account_type > 0")
        ->count();
    // $sqlCount=$User->count();
    // die;
    set_time_limit(0);
    ini_set('memory_limit', '512M');


    $sqlLimit = 100000;//每次只从数据库取100000条以防变量缓存太大
    // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小

    $fileNameArr = array();
    // 逐行取出数据，不浪费内存
    for ($i = 0; $i < ceil($sqlCount / $sqlLimit); $i++) {

        // $Users=$User->field("id , mobile , real_name , id_card , accountid , account_type")->where("user_banks  status = 0 and")->limit($i*$sqlLimit,100000)->select();
        $Users=$User ->join('wm_user_banks   as ub ON us.id = ub.user_id')
            ->field(" us.mobile , us.real_name , us.id_card , us.accountid ")
            ->where("ub.status = 0 and us.account_type > 0")->limit($i*$sqlLimit,100000)
            // ->fetchSql(true)
            ->select();
        //为fputcsv()函数打开文件句柄
        $output = fopen('php://output', 'w') or die("can't open php://output");
        //告诉浏览器这个是一个csv文件
        $filename = "买单侠合投用户" ;
       /* header("Content-Type: application/csv");
        header("Content-Disposition: attachment; filename=$filename.xlsx");*/

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename.xls");
        //输出表头
        //$table_head = array('mobile','real_name','id_card', 'accountid');
        $table_head = array('手机号','姓名','身份证号', '电子账号');
        foreach ($table_head as $tval) {
            $tval=iconv("UTF-8", "GB2312", $tval);
            echo $tval . "\t";
        }
        echo "\n";
        foreach ($Users as $e) {
            //    unset($e['xx']);//若有多余字段可以使用unset去掉
            //    $e['xx'] = isset($e['xxx']) ? "xx" : 'x'; //可以根据需要做相应处理
            //输出内容
            $e['mobile']=iconv("UTF-8", "GB2312", $e['mobile']);
            $e['real_name']=iconv("UTF-8", "GB2312", $e['real_name']);
            $e['id_card']=iconv("UTF-8", "GB2312", $e['id_card']);
            $e['accountid']=iconv("UTF-8", "GB2312", $e['accountid']);
            echo $e['mobile'] . "\t";
            echo $e['real_name'] . "\t";
            echo $e['id_card'] . "\t";
            echo $e['accountid'] . "\t";
            echo "\n";
           // fputcsv($output, array_values($e));
        }

      //  fputcsv($output, $table_head);
        //输出每一行数据到文件中
        foreach ($Users as $e) {
            //    unset($e['xx']);//若有多余字段可以使用unset去掉
            //    $e['xx'] = isset($e['xxx']) ? "xx" : 'x'; //可以根据需要做相应处理
            //输出内容
           // fputcsv($output, array_values($e));
        }
        // echo 111;
        // unset('memory_limit');
        fclose($output);

    }

}



    public function index3()
    {

        $DB_w = C("DB_URL");
        $User = M("user_users as us",'wm_',$DB_w);
        $sqlCount=$User
            ->count();
        // $sqlCount=$User->count();
        // die;
        set_time_limit(0);
        ini_set('memory_limit', '512M');


        $sqlLimit = 100000;//每次只从数据库取100000条以防变量缓存太大
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小

        $fileNameArr = array();
        // 逐行取出数据，不浪费内存
        for ($i = 0; $i < ceil($sqlCount / $sqlLimit); $i++) {

            // $Users=$User->field("id , mobile , real_name , id_card , accountid , account_type")->where("user_banks  status = 0 and")->limit($i*$sqlLimit,100000)->select();
            $Users=$User->field(" us.mobile , us.real_name , us.id_card , us.accountid ")
               ->limit($i*$sqlLimit,100000)
                // ->fetchSql(true)
                ->select();
            //为fputcsv()函数打开文件句柄
            $output = fopen('php://output', 'w') or die("can't open php://output");
            //告诉浏览器这个是一个csv文件
            $filename = "买单侠合投用户" ;
           // header("Content-Type: application/xls");
            header("Content-type:application/vnd.ms-excel;charset=UTF-8");
            header("Content-Disposition: attachment; filename=$filename.xls");
            //输出表头
            //$table_head = array('mobile','real_name','id_card', 'accountid');

            $table_head = array('手机号','姓名','身份证号', '电子账号');
            foreach ($table_head as $tval) {
                echo $tval . "\t";
            }
            echo "\n";
            foreach ($Users as $e) {
                //    unset($e['xx']);//若有多余字段可以使用unset去掉
                //    $e['xx'] = isset($e['xxx']) ? "xx" : 'x'; //可以根据需要做相应处理
                //输出内容
                $e['mobile']=iconv("UTF-8", "GB2312", $e['mobile']);
                //$e['real_name']=iconv("UTF-8", "GB2312", $e['real_name']);

                echo $e['mobile'] . "\t";
                echo $e['real_name'] . "\t";
                //echo iconv(“UTF-8”, “GBK”, $e['real_name'] ).”\t”;
                echo iconv("UTF-8", "GBK", $e['real_name']). "\t";
                echo $e['id_card'] . "\t";
                echo $e['accountid'] . "\t";
                echo "\n";
               // fputcsv($output, array_values($e));
            }


            echo "\n";

           // fputcsv($output, $table_head);
            //输出每一行数据到文件中
          /*  foreach ($Users as $e) {
                //    unset($e['xx']);//若有多余字段可以使用unset去掉
                //    $e['xx'] = isset($e['xxx']) ? "xx" : 'x'; //可以根据需要做相应处理
                //输出内容
                fputcsv($output, array_values($e));
            }*/
            // echo 111;
            // unset('memory_limit');
           // fclose($output);

        }

    }




    public function index2()
    {

        $DB_w = C("DB_URL");
        $User = M("user_users as us",'wm_',$DB_w);
        $sqlCount=$User ->join('wm_user_banks   as ub ON us.id = ub.user_id')
            // ->field("us.id ")
            ->where("ub.status = 0 and us.account_type > 0")
            ->count();
        // $sqlCount=$User->count();
        // die;
        set_time_limit(0);
        ini_set('memory_limit', '512M');


        $sqlLimit = 100000;//每次只从数据库取100000条以防变量缓存太大
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小

        $fileNameArr = array();
        // 逐行取出数据，不浪费内存
        for ($i = 0; $i < ceil($sqlCount / $sqlLimit); $i++) {

            // $Users=$User->field("id , mobile , real_name , id_card , accountid , account_type")->where("user_banks  status = 0 and")->limit($i*$sqlLimit,100000)->select();
            $Users=$User ->join('wm_user_banks   as ub ON us.id = ub.user_id')
                ->field(" us.mobile , us.real_name , us.id_card , us.accountid ")
                ->where("ub.status = 0 and us.account_type > 0")->limit($i*$sqlLimit,100000)
                // ->fetchSql(true)
                ->select();
            //为fputcsv()函数打开文件句柄
            $output = fopen('php://output', 'w') or die("can't open php://output");
            //告诉浏览器这个是一个csv文件
            $filename = "买单侠合投用户" ;
           /* header("Content-Type: application/csv");
            header("Content-Disposition: attachment; filename=$filename.xlsx");*/

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=sample.xls");
            header("Pragma:no-cache");
            header("Expires:0");
            //输出表头
            //$table_head = array('mobile','real_name','id_card', 'accountid');
            $table_head = array('手机号','姓名','身份证号', '电子账号');
            fputcsv($output, $table_head);
            //输出每一行数据到文件中
            foreach ($Users as $e) {
                //    unset($e['xx']);//若有多余字段可以使用unset去掉
                //    $e['xx'] = isset($e['xxx']) ? "xx" : 'x'; //可以根据需要做相应处理
                //输出内容
                fputcsv($output, array_values($e));
            }
            // echo 111;
            // unset('memory_limit');
            fclose($output);

        }

    }


    /**
     * @creator Jimmy
     * @data 2018/1/05
     * @desc 数据导出到excel(csv文件)
     * @param $filename 导出的csv文件名称 如date("Y年m月j日").'-test.csv'
     * @param array $tileArray 所有列名称
     * @param array $dataArray 所有列数据
     */
    public  function exportToExcel($filename, $tileArray=[], $dataArray=[]){
        ini_set('memory_limit','512M');
        ini_set('max_execution_time',0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=".$filename);
        $fp=fopen('php://output','w');
        fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));//转码 防止乱码(比如微信昵称(乱七八糟的))
        fputcsv($fp,$tileArray);
        $index = 0;
        foreach ($dataArray as $item) {
            if($index==1000){
                $index=0;
                ob_flush();
                flush();
            }
            $index++;
            fputcsv($fp,$item);
        }

        ob_flush();
        flush();
        ob_end_clean();
    }



    /**
     * 导出到EXCEL
     * @param type $expTitle
     * @param type $expCellName
     * @param type $expTableData
     */
    function exportExcel($expTitle, $expCellName, $expTableData) {
        $xlsTitle = iconv('utf-8', 'utf-8', $expTitle); //文件名称
        $fileName = $expTitle . date('_YmdHis'); //or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("Excel.PHPExcel");
        $objPHPExcel = new \PHPExcel();

        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
//  $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1'); //合并单元格
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 2), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }



        /*
         * 测试
         */
    public function text()
    {
//获取所需数据
        $DB_w = C("DB_URL");
        $User = M("user_users as us",'wm_',$DB_w);
        $res=$User->field("id , mobile , real_name , id_card , accountid , account_type")->limit(10)->select();

//dump($res);//
//die;

        $export = 1;//触发导出指令的值
        if ($export == 1) {
           echo $xlsName = "订单列表";//表名
            $xlsCell = array(      //这里是写字段名称的
                         array('id', '订单号') ,
                         array('real_name', '订购金额') ,
                         array('id_card', '优惠金额') ,
                         );

           // dump($xlsCell);
                    $xlsData = array();
                      foreach ($res as $key => $val) {

                          echo $val[real_name];
                          array_push($xlsData, array(  //这里的需要导出的内容，要注意键名跟上面的字段键名要一致
                              'id' => " " . $val[id],
                              'real_name' => $val[real_name],
                              'id_card' => $val[id_card],
                          ));
                      }
           // dump($xlsData);
                exportExcel($xlsName, $xlsCell, $xlsData);//这里就是调用写在function里的函数了。
                die();
         }

    }



    public function text2()
    {
        vendor("Excel.PHPExcel");
        // 创建一个处理对象实例
        $objExcel = new \PHPExcel();

        // 创建文件格式写入对象实例, uncomment
        //$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式
        //or
        $objWriter = new \PHPExcel_Writer_Excel2007($objExcel); // 用于 2007 格式
        //$objWriter->setOffice2003Compatibility(true);


        //设置文档基本属性
        $objProps = $objExcel->getProperties();
        $objProps->setCreator("Zeal Li");
        $objProps->setLastModifiedBy("Zeal Li");
        $objProps->setTitle("Office XLS Test Document");
        $objProps->setSubject("Office XLS Test Document, Demo");
        $objProps->setDescription("Test document, generated by PHPExcel.");
        $objProps->setKeywords("office excel PHPExcel");
        $objProps->setCategory("Test");
        //设置当前的sheet索引，用于后续的内容操作。
        //一般只有在使用多个sheet的时候才需要显示调用。
        //缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0
        $objExcel->setActiveSheetIndex(0);

        $objActSheet = $objExcel->getActiveSheet();

        //设置当前活动sheet的名称
        $objActSheet->setTitle('Sheet1');


        //设置单元格内容

        //由PHPExcel根据传入内容自动判断单元格内容类型
        $objActSheet->setCellValue('A1', '字符串内容'); // 字符串内容
        $objActSheet->setCellValue('A2', 26);            // 数值
        $objActSheet->setCellValue('A3', true);          // 布尔值
        $objActSheet->setCellValue('A4', '=SUM(A2:A2)'); // 公式

        //显式指定内容类型
        $objActSheet->setCellValueExplicit('A5', '847475847857487584', \PHPExcel_Cell_DataType::TYPE_STRING);

        //合并单元格
        //$objActSheet->mergeCells('B1:C22');

        //分离单元格
       // $objActSheet->unmergeCells('B1:C22');

        //设置单元格样式

        //设置宽度
        $objActSheet->getColumnDimension('B')->setAutoSize(true);
        $objActSheet->getColumnDimension('A')->setWidth(30);

        $objStyleA5 = $objActSheet->getStyle('A5');

        //设置单元格内容的数字格式。

        //如果使用了 PHPExcel_Writer_Excel5 来生成内容的话，
        //这里需要注意，在 PHPExcel_Style_NumberFormat 类的 const 变量定义的
        //各种自定义格式化方式中，其它类型都可以正常使用，但当setFormatCode
        //为 FORMAT_NUMBER 的时候，实际出来的效果被没有把格式设置为"0"。需要
        //修改 PHPExcel_Writer_Excel5_Format 类源代码中的 getXf($style) 方法，
        //在 if ($this->_BIFF_version == 0x0500) { （第363行附近）前面增加一
        //行代码:
        //if($ifmt === '0') $ifmt = 1;

        //设置格式为PHPExcel_Style_NumberFormat::FORMAT_NUMBER，避免某些大数字
        //被使用科学记数方式显示，配合下面的 setAutoSize 方法可以让每一行的内容
        //都按原始内容全部显示出来。
        $objStyleA5 ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

        //设置字体
        $objFontA5 = $objStyleA5->getFont();
        $objFontA5->setName('Courier New');
        $objFontA5->setSize(10);
        $objFontA5->setBold(true);
        $objFontA5->setUnderline(\PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $objFontA5->getColor()->setARGB('FF999999');

        //设置对齐方式
        $objAlignA5 = $objStyleA5->getAlignment();
        $objAlignA5->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objAlignA5->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

       /* //设置边框
        $objBorderA5 = $objStyleA5->getBorders();
        $objBorderA5->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objBorderA5->getTop()->getColor()->setARGB('FFFF0000'); // color
        $objBorderA5->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objBorderA5->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objBorderA5->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        //设置填充颜色
        $objFillA5 = $objStyleA5->getFill();
        $objFillA5->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objFillA5->getStartColor()->setARGB('FFEEEEEE');*/

        //从指定的单元格复制样式信息.
        $objActSheet->duplicateStyle($objStyleA5, 'B1:C22');

        /*//添加图片
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('ZealImg');
        $objDrawing->setDescription('Image inserted by Zeal');
        $objDrawing->setPath('./zeali.net.logo.gif');
        $objDrawing->setHeight(36);
        $objDrawing->setCoordinates('C23');
        $objDrawing->setOffsetX(10);
        $objDrawing->setRotation(15);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->getShadow()->setDirection(36);
        $objDrawing->setWorksheet($objActSheet);*/

        //添加一个新的worksheet
        $objExcel->createSheet();
        $objExcel->getSheet(1)->setTitle('测试2');

        //保护单元格
        $objExcel->getSheet(1)->getProtection()->setSheet(true);
        $objExcel->getSheet(1)->protectCells('A1:C22', 'PHPExcel');

        //输出内容

        $outputFileName = "output.xls";
        //到文件
        ////$objWriter->save($outputFileName);
        //or
        //到浏览器
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');

    }



    /**
     * 数组转xls格式的excel文件
     * @param  array  $data      需要生成excel文件的数组
     * @param  string $filename  生成的excel文件名
     *      示例数据：
    $data = array(
    array(NULL, 2010, 2011, 2012),
    array('Q1',   12,   15,   21),
    array('Q2',   56,   73,   86),
    array('Q3',   52,   61,   69),
    array('Q4',   30,   32,    0),
    );
     */
    function create_xls($data,$filename='simple.xls'){
        ini_set('max_execution_time', '0');
       // Vendor('PHPExcel.PHPExcel');
        vendor("Excel.PHPExcel");
        $filename=str_replace('.xls', '', $filename).'.xls';
        $phpexcel = new \PHPExcel();
        $phpexcel->getProperties()
            ->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $phpexcel->getActiveSheet()->fromArray($data);
        $phpexcel->getActiveSheet()->setTitle('Sheet1');
        $phpexcel->setActiveSheetIndex(0);
        //$phpexcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

        $phpexcel->getActiveSheet()->getStyle('A')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $phpexcel->getActiveSheet()->getStyle('B')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        $phpexcel->getActiveSheet()->getStyle('C')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $phpexcel->getActiveSheet()->getStyle('D')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $phpexcel->getActiveSheet()->getStyle('E')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $phpexcel->getActiveSheet()->getStyle('F')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        /*
        $phpexcel->getColumnDimension('A')->setWidth(30);
        $phpexcel->getColumnDimension('B')->setWidth(80);*/

        $ex = '2007';
        $timestamp = time();
        if($ex == '2007') { //导出excel2007文档
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="买单侠合投用户.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else { //导出excel2003文档
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="links_out'.$timestamp.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0


        $objwriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
        $objwriter->save('php://output');

        //1.6.2新版保存
       // require_once('Classes/PHPExcel/IOFactory.php');
        $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
        $objwriter->save('php://output');
        //$objWriter->save(str_replace('.php', '.xls', __FILE__));
        exit;
    }

    function index5(){


        $DB_w = C("DB_URL");
        $User = M("user_users as us",'wm_',$DB_w);
        $sqlCount=$User
            ->count();
        // $sqlCount=$User->count();
        // die;
        set_time_limit(0);
        ini_set('memory_limit', '512M');


        $sqlLimit = 100000;//每次只从数据库取100000条以防变量缓存太大
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小

        $data[] = array('手机号','姓名','身份证号', '电子账号');
        // 逐行取出数据，不浪费内存
       // for ($i = 0; $i < ceil($sqlCount / $sqlLimit); $i++) {

            // $Users=$User->field("id , mobile , real_name , id_card , accountid , account_type")->where("user_banks  status = 0 and")->limit(0,200000)->select();
            $Users=$User->field(" us.mobile , us.real_name , us.id_card , us.accountid ")
              //  ->limit($i*$sqlLimit,200000)
              //->limit(0,50)
                // ->fetchSql(true)
                ->select();

            $filename = "买单侠合投用户" ;


            foreach ($Users as $e) {
                $e['mobile']                = html_entity_decode("&iuml;&raquo;&iquest;".$e['mobile']);
                $data[] =array($e['mobile'], $e['real_name'], $e['id_card'], $e['accountid']);


            }
           // dump($data);die;


       // }

        $this->create_xls($data);die;

    }


    function index6(){


    $DB_w = C("DB_URL");
    $User = M("user_users as us",'wm_',$DB_w);
    $sqlCount=$User
        ->count();
    // $sqlCount=$User->count();
    // die;
    set_time_limit(0);
    ini_set('memory_limit', '512M');


    $sqlLimit = 100000;//每次只从数据库取100000条以防变量缓存太大
    // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小

    $data[] = array('手机号','姓名','身份证号', '电子账号' , '用户类型' , '银行卡号');
    // 逐行取出数据，不浪费内存
    // for ($i = 0; $i < ceil($sqlCount / $sqlLimit); $i++) {

    // $Users=$User->field("id , mobile , real_name , id_card , accountid , account_type")->where("user_banks  status = 0 and")->limit(0,200000)->select();
   /* $Users=$User->field(" us.mobile , us.real_name , us.id_card , us.accountid ")
        //  ->limit($i*$sqlLimit,200000)
        //->limit(0,50)
        // ->fetchSql(true)
        ->select();*/


    $Users=$User ->join('wm_user_banks   as ub ON us.id = ub.user_id')
        ->field(" us.mobile , us.real_name , us.id_card , us.accountid , us.account_type , ub.card_no ")
        ->where("us.status = 0 and us.account_type < 0")
        //->limit($i*$sqlLimit,100000)
        // ->fetchSql(true)
        ->select();

    $filename = "买单侠合投用户" ;


    foreach ($Users as $e) {

        /*$e['mobile']= html_entity_decode("&iuml;&raquo;&iquest;".$e['mobile']);
        $e['id_card']= html_entity_decode("&iuml;&raquo;&iquest;".$e['id_card']);
        $e['accountid'] = html_entity_decode("&iuml;&raquo;&iquest;".$e['accountid']);
        $e['card_no']= html_entity_decode("&iuml;&raquo;&iquest;".$e['card_no']);
        //$e['accountid']=string($e['accountid']);*/

        $e['mobile'] .=  ' ';
        $e['real_name'] .=  ' ';
        $e['id_card'] .=  ' ';
        $e['card_no'] .=  ' ';
        $e['accountid'] .=  ' ';
        $e['account_type'] .=  ' ';

        $data[] =array($e['mobile'], $e['real_name'],  (string)$e['id_card'], (string)$e['accountid'], $e['account_type'], $e['card_no']);


    }
    // dump($data);die;


    // }

    $this->create_xls($data);die;

}





}
