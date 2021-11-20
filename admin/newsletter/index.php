<?php

// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true):

// Include config file
require_once "../config/conn.php";

// Dias Asc
$countLast  = 1;
$count      = 1;
$diasAsc    = array();
$sql        = mysqli_query($conn, "SELECT DISTINCT created_at FROM leads ORDER BY created_at ASC");

while($row=mysqli_fetch_assoc($sql)) { $diasAsc[$count] = $row; $count++; }

// Dias Desc
$diasDesc = array_reverse($diasAsc);

// Ultimos 50 registros da newsletter.
$count = 1;
$leads = array();
$sql   = mysqli_query($conn, "SELECT * FROM leads ORDER BY created_at DESC LIMIT 50");

while($row=mysqli_fetch_assoc($sql)) { $leads[$count] = $row; $count++; }

// Filtro lead por data
if($_SERVER["REQUEST_METHOD"] == "POST") {

    $is_posted  = true;
    $count      = 1;
    $dia_de     = $_POST["dia_de"];
    $dia_ate    = $_POST["dia_ate"];

    if(!empty($dia_de) && !empty($dia_ate)){

        $sql = mysqli_query($conn, "SELECT * FROM leads WHERE created_at BETWEEN '".$dia_de."' AND '".$dia_ate."' ORDER BY created_at DESC");
        while($row=mysqli_fetch_assoc($sql)) {
            $leadsResult[$count] = $row; $count++;
        }

        require_once "_config.php";


    }

} else {
    $is_posted  = false;
    $download  = false;     
}

?>
 
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Newsletter | Contatos cadastrados </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="container">
            <div class="col-12">
                <h2 class="mt-5">Newsletter</h2>
            </div>
        </div>

        <div class="container">
            <div class="col-12">
            
                <div class="row">
                    <div class="col-6">
                        <nav>
                            <form method="post">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label>De</label>
                                            <select name="dia_de" class="form-control">
                                                    
                                                <?php if($is_posted) { ?>
                                                <option value="<?php echo $dia_de; ?>">
                                                    <?php

                                                        $dia_dePosted = new DateTime($dia_de); 
                                                        echo $dia_dePosted->format('d/m/Y');

                                                    ?>
                                                </option>
                                                <?php } ?>

                                                <?php foreach ($diasAsc as $diaAsc) { ?>
                                                <option value="<?php echo $diaAsc['created_at'] ?>">
                                                    <?php
      
                                                        $diaAscFormat = new DateTime($diaAsc['created_at']); 
                                                        echo $diaAscFormat->format('d/m/Y');

                                                    ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>   
                                    <div class="col-5 px-0">
                                        <div class="form-group">
                                            <label>Até</label>
                                            <select name="dia_ate" class="form-control">

                                                <?php if($is_posted) { ?>
                                                <option value="<?php echo $dia_ate; ?>">
                                                    <?php

                                                        $dia_atePosted = new DateTime($dia_ate); 
                                                        echo $dia_atePosted->format('d/m/Y');

                                                    ?>
                                                </option>
                                                <?php } ?>

                                                <?php foreach ($diasDesc as $diaDesc) { ?>
                                                <option value="<?php echo $diaDesc['created_at'] ?>">
                                                    <?php

                                                        $diaDescFormat = new DateTime($diaDesc['created_at']); 
                                                        echo $diaDescFormat->format('d/m/Y');

                                                    ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>   
                                    <div class="col-2">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary mt-4" value="Filtrar">
                                        </div>
                                    </div>   
                                </div>
                            </form>
                        </nav>
                    </div>
                    <?php if($download): ?>
                    <div class="col-12">
                        <a href="<?php echo $downloadLink?>" class="btn btn-dark mt-4 mb-4" download>
                            Faça o download da sua planilha
                        </a>
                        
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col">Email</th>
                        <th scope="col">Dia</th>
                        <th scope="col">Hora</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                            // Processing form data when form is submitted
                            if($is_posted){
                                
                                foreach ($leadsResult as $leadResult) {
                        ?>

                            <tr>
                                <th>
                                    <?php echo $leadResult['email']; ?>
                                </th>
                                <td>
                                    <?php 
                                        
                                        $dia = new DateTime($leadResult['created_at']); 
                                        echo $dia->format('d/m/Y');
                                        
                                    ?>
                                </td>
                                <td>
                                    <?php 

                                        $hora = new DateTime($leadResult['created_at']); 
                                        echo $hora->format('H:i:s');

                                    ?>
                                </td>
                            </tr>

                        <?php
                                }

                            }  else {

                            // Ultimos 50 registros da newsletter.
                            foreach ($leads as $lead) {
                        ?>

                        <tr>
                            <th>
                                <?php echo $lead['email']; ?>
                            </th>
                            <td>
                                <?php  
                                    
                                    $dia = new DateTime($lead['created_at']); 
                                    echo $dia->format('Y/m/d');
                                
                                ?>
                            </td>
                            <td>
                                <?php  
                                    
                                    $hora = new DateTime($lead['created_at']); 
                                    echo $hora->format('H:i:s');
                                
                                ?>
                            </td>
                        </tr>

                        <?php } } ?>


                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
           var diaDeList = [];
           var diaAteList = [];

           $('select[name="dia_de"] option').each(function() {
                if($.inArray(this.text, diaDeList) >-1){
                    $(this).remove()
                }else{
                    diaDeList.push(this.text);
                }
           });

           $('select[name="dia_ate"] option').each(function() {
                if($.inArray(this.text, diaAteList) >-1){
                    $(this).remove()
                }else{
                    diaAteList.push(this.text);
                }
           });

        });
    </script>
</body>
</html>
<?php else: header("location: ../"); exit; endif; ?>