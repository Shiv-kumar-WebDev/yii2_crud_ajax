<?php

/** @var yii\web\View $this */

$this->title = 'To-do List Application';
?>
<div style="display: grid;grid-template-columns:1fr 4fr;padding: 2rem 5rem;">
    <h1>handysolver</h1>
    <div>
        <h2 style="text-align: center;">To-do List Application</h2>
        <p style="text-align: center;">Where to-do items are added/deleted and belong to categories</p>
    </div>
</div>
<div>
    <div style="display: flex;padding:2rem 5rem;justify-content:center;gap:4rem">
        <div>
            <select id="category">
                <option value="">Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <div>
                <input style="width:500px" type="text" id="toDoName">
                <button style="background: green;color:white" id="addTodo">Add</button>
            </div>
        </div>
    </div>
    <div style="padding: 2rem 5rem;">
        <table border="1" width="100%">
            <tr style="background: lightgray;">
                <td>Todo item name</td>
                <td>Category</td>
                <td>Timestamp</td>
                <td>Actions</td>
            </tr>
            <?php foreach ($toDos as $toDo): ?>
            <tr data-id="<?= $toDo['id']; ?>"> <!-- Add data-id for the row -->
                <td><?= $toDo['todo_name']; ?></td>
                <td><?= $toDo['category_name']; ?></td>
                <td><?php $date = new DateTime($toDo['timestamp']);
                echo $date->format('jS F'); ?></td>
                <td><a href="javascript:void(0);" class="delete-todo" style="background: red; color: white; padding: .3rem; text-decoration: none;">Delete</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>