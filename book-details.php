<?php
require "includes/database.inc.php";

if (isset($_GET['id'])) {
  
  $bookID = $_GET['id'];

  $bookQuery = "SELECT * FROM Books WHERE ID = ?";
  $bookStmt = $pdo->prepare($bookQuery);
  $bookStmt->execute([$bookID]);
  $bookDetails = $bookStmt->fetch(PDO::FETCH_ASSOC);

  $authorsQuery = "SELECT AuthorId FROM BookAuthors WHERE BookId = ?";
  $authorsStmt = $pdo->prepare($authorsQuery);
  $authorsStmt->execute([$bookID]);
  $authorsData = $authorsStmt->fetchAll(PDO::FETCH_ASSOC);

  $authors = []; 

  foreach ($authorsData as $authorData) {
    $authorID = $authorData['AuthorId'];

    $authorDetailsQuery = "SELECT * FROM Authors WHERE ID = ?";
    $authorDetailsStmt = $pdo->prepare($authorDetailsQuery);
    $authorDetailsStmt->execute([$authorID]);
    $authorDetails = $authorDetailsStmt->fetch(PDO::FETCH_ASSOC);

    $authors[] = $authorDetails['FirstName'] . ' ' . $authorDetails['LastName'];
  }

  $subCategoryID = $bookDetails['SubcategoryID'];
  $subCategoryQuery = "SELECT SubcategoryName FROM Subcategories WHERE ID = ?";
  $subCategoryStmt = $pdo->prepare($subCategoryQuery);
  $subCategoryStmt->execute([$subCategoryID]);
  $SubcategoriesDetails = $subCategoryStmt->fetch(PDO::FETCH_ASSOC);

  $imprintID = $bookDetails['ImprintID'];
  $imprintQuery = "SELECT Imprint FROM Imprints WHERE ID = ?";
  $imprintStmt = $pdo->prepare($imprintQuery);
  $imprintStmt->execute([$imprintID]);
  $imprintDetails = $imprintStmt->fetch(PDO::FETCH_ASSOC);

  $bindingTypeID = $bookDetails['BindingTypeID'];
  $bindingTypeQuery = "SELECT BindingType FROM BindingTypes WHERE ID = ?";
  $bindingTypeStmt = $pdo->prepare($bindingTypeQuery);
  $bindingTypeStmt->execute([$bindingTypeID]);
  $bindingTypeDetails = $bindingTypeStmt->fetch(PDO::FETCH_ASSOC);

  $productionStatusID = $bookDetails['ProductionStatusID'];
  $productionStatusQuery = "SELECT ProductionStatus FROM ProductionStatuses WHERE ID = ?";
  $productionStatusStmt = $pdo->prepare($productionStatusQuery);
  $productionStatusStmt->execute([$productionStatusID]);
  $productionStatusDetails = $productionStatusStmt->fetch(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta http-equiv="Content-Type" content="text/html; 
   charset=UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="author" content="">
   <title>Book Template</title>

   <link rel="shortcut icon" href="../../assets/ico/favicon.png">   

   <!-- Bootstrap core CSS -->
   <link href="bootstrap3_bookTheme/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Bootstrap theme CSS -->
   <link href="bootstrap3_bookTheme/theme.css" rel="stylesheet">


   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!--[if lt IE 9]>
   <script src="bootstrap3_bookTheme/assets/js/html5shiv.js"></script>
   <script src="bootstrap3_bookTheme/assets/js/respond.min.js"></script>
   <![endif]-->
</head>

<body>

<?php include 'includes/book-header.inc.php'; ?>
   
<div class="container">
   <div class="row">  <!-- start main content row -->

      <div class="col-md-2">  <!-- start left navigation rail column -->
         <?php include 'includes/book-left-nav.inc.php'; ?>
      </div>  <!-- end left navigation rail --> 

      <div class="col-md-10">  <!-- start main content column -->
        
         <!-- book panel  -->
         <div class="panel panel-danger spaceabove">           
           <div class="panel-heading"><h4>Book Details</h4></div>
           
           <table class="table">
             <tr>
               <th>Cover</th>
               <td><?php $imagePath = 'images/tinysquare/' . $bookDetails['ISBN10'] . '.jpg'; ?>
                     <?php if (file_exists($imagePath)) : ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo $bookDetails['Title']; ?>">
                     <?php else : ?>
                        No Image Available
                     <?php endif; ?>
                </td>
             </tr>            
             <tr>
               <th>Title</th>
               <td><em><?php echo $bookDetails['Title']; ?></em></td>
             </tr>    
             <tr>
               <th>Authors</th>
               <td>
               <?php echo implode('<br>', $authors); ?>
               </td>
             </tr>   
             <tr>
               <th>ISBN-10</th>
               <td><?php echo $bookDetails['ISBN10']; ?></td>
             </tr>
             <tr>
               <th>ISBN-13</th>
               <td><?php echo $bookDetails['ISBN13']; ?></td>
             </tr>
             <tr>
               <th>Copyright Year</th>
               <td><?php echo $bookDetails['CopyrightYear']; ?></td>
             </tr>   
             <tr>
               <th>Instock Date</th>
                <td>
                <?php echo date('Y-m-d', strtotime($bookDetails['LatestInstockDate'])); ?>
               </td>
             </tr>              
             <tr>
               <th>Trim Size</th>
               <td><?php echo $bookDetails['TrimSize']; ?></td>
             </tr> 
             <tr>
               <th>Page Count</th>
               <td><?php echo $bookDetails['PageCountsEditorialEst']; ?></td>
             </tr> 
             <tr>
               <th>Description</th>
               <td><?php echo $bookDetails['Description']; ?></td>
             </tr> 
             <tr>
               <th>Sub Category</th>
               <td><?php echo $SubcategoriesDetails['SubcategoryName']; ?></td>
             </tr>
             <tr>
               <th>Imprint</th>
               <td><?php echo $imprintDetails['Imprint']; ?></td>
             </tr>   
             <tr>
               <th>Binding Type</th>
               <td><?php echo $bindingTypeDetails['BindingType']; ?></td>
             </tr> 
             <tr>
               <th>Production Status</th>
               <td><?php echo $productionStatusDetails['ProductionStatus']; ?></td>
             </tr>              
           </table>

         </div>           
      </div>



      </div>  <!-- end main content column -->
   </div>  <!-- end main content row -->
</div>   <!-- end container -->
   


   
   
 <!-- Bootstrap core JavaScript
 ================================================== -->
 <!-- Placed at the end of the document so the pages load faster -->
 <script src="bootstrap3_bookTheme/assets/js/jquery.js"></script>
 <script src="bootstrap3_bookTheme/dist/js/bootstrap.min.js"></script>
 <script src="bootstrap3_bookTheme/assets/js/holder.js"></script>
</body>
</html>