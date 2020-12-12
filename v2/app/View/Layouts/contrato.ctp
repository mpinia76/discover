<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
    </head>
    <style>
        body{
            font-family: arial;
            font-size: 14px;
        }
        .content{
            width: 900px;
        }
        
		.content2{
            width: 725px;
            margin-left: 50px;
			
        }
        .content3{
            width: 775px;
            margin-right: 100px;
			
        }
        hr{
            height: 2px;
            color: #000;
            background-color: #000;
        }
        h1{
            font-size: 14px;
        }
        h2{
            font-size: 14px;
            color: #fff;
            display: block;
            padding:2px;
            background: #092f87;
        }
        
       table.test {
		    border-collapse: separate;
		    border-spacing: 0px;
		    *border-collapse: expression('separate', cellSpacing = '10px');
		}
        table.test td{
            padding: 0;
            border-spacing: 0;
        }
        table.test tr{
            padding: 0;
            border-spacing: 0;
        }
        td.border{
            border-bottom: 1px solid #000;
        }
        td.border2{
            border-bottom: 1.5px solid #000;
            border-top: 1.5px solid #000;
            border-left: 1.5px solid #000;
            border-right: 1.5px solid #000;
        }
    </style>
    <body>
        <?php echo $this->fetch('content'); ?>
    </body>
</html>