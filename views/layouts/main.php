<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <head>
    <!-- Include Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- Include jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

    <style>
        td,select,input,button{
            padding: .5rem;
        }
        td{
            border-right:1px solid black;
            border-left:1px solid black;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    
</header>


        <?= $content ?>
  



<?php $this->endBody() ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
    $('#addTodo').on('click', function() {
        var toDoName = $('#toDoName').val();
        var categoryId = $('#category').val();
        
        if (!toDoName) {
            toastr.error('Please enter a to-do item name.');
            return;
        }
        if (!categoryId) {
            toastr.error('Please select a category.');
            return;
        }

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['site/add-todo']) ?>', // Update this to your action URL
            type: 'POST',
            data: {
                toDoName: toDoName,
                categoryId: categoryId,
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>' // CSRF token
            },
            success: function(response) {
                if (response.success) {
                    $('#toDoName').val(''); // Clear the input

                    // Append new row to the table
                    var newRow = '<tr data-id="' + response.id + '">' +
                        '<td>' + htmlspecialchars(toDoName) + '</td>' +
                        '<td>' + htmlspecialchars($('#category option:selected').text()) + '</td>' +
                        '<td>' + formatDate(new Date()) + '</td>' + // Current date
                        '<td><a href="javascript:void(0);" class="delete-todo" style="background: red;color:white;padding:.3rem;text-decoration: none;">Delete</a></td>' +
                        '</tr>';
                    
                    $('table').append(newRow); // Append the new row to the table
                    toastr.success(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    // Function to escape HTML for security
    function htmlspecialchars(str) {
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Function to format date in 'jS F' format
    function formatDate(date) {
        var day = date.getDate(); // Get the day
        var month = date.toLocaleString('default', { month: 'long' }); // Get the full month name
        
        // Add suffix for day
        var suffix = ['th', 'st', 'nd', 'rd'][((day % 10) < 4 && (day % 100 - day % 10 != 10)) ? day % 10 : 0];
        return day + suffix + ' ' + month; // Return formatted date
    }
});



    $(document).ready(function() {
    // Delete to-do item
    $(document).on('click', '.delete-todo', function() {
        var row = $(this).closest('tr'); // Get the closest table row
        var id = row.data('id'); // Get the ID from the row

        if (confirm('Are you sure you want to delete this to-do item?')) {
            $.ajax({
                url: '<?= \yii\helpers\Url::to(['site/delete-todo']) ?>',
                type: 'POST',
                data: {
                    id: id,
                    _csrf: '<?= Yii::$app->request->getCsrfToken() ?>' // CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        if(response.message!='To-do item deleted successfully.'){
                            
                            toastr.success(response.message);
                        }
                        toastr.success(response.message);
                        row.remove(); // Remove the row from the table
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});

</script>


</body>
</html>
<?php $this->endPage() ?>
