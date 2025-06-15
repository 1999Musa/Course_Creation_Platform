<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Course</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .module, .content {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
        }
        .content {
            margin-left: 20px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Create a New Course</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="courseForm" method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Course Title</label>
            <input type="text" name="title" class="form-control" required />
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" />
        </div>

        <hr>
        <h4>Modules</h4>
        <div id="modules-container"></div>
        <button type="button" class="btn btn-secondary" id="add-module">Add Module</button>

        <br><br>
        <button type="submit" class="btn btn-success">Save Course</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let moduleIndex = 0;

    $('#add-module').click(function () {
        const moduleHtml = `
        <div class="module">
            <h5>Module</h5>
            <div class="mb-2">
                <label>Module Title</label>
                <input type="text" name="modules[${moduleIndex}][title]" class="form-control" required>
            </div>
            <div class="content-container"></div>
            <button type="button" class="btn btn-sm btn-primary add-content" data-module-index="${moduleIndex}">Add Content</button>
            <button type="button" class="btn btn-sm btn-danger remove-module mt-2">Remove Module</button>
        </div>`;
        $('#modules-container').append(moduleHtml);
        moduleIndex++;
    });

    $(document).on('click', '.remove-module', function () {
        $(this).closest('.module').remove();
    });

    $(document).on('click', '.remove-content', function () {
        $(this).closest('.content').remove();
    });

    $(document).on('click', '.add-content', function () {
        const moduleIndex = $(this).data('module-index');
        const container = $(this).siblings('.content-container');
        const contentIndex = container.children('.content').length;

        const contentHtml = `
        <div class="content">
            <h6>Content</h6>
            <div class="mb-2">
                <label>Type</label>
                <select name="modules[${moduleIndex}][contents][${contentIndex}][type]" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="text">Text</option>
                    <option value="image">Image URL</option>
                    <option value="video">Video URL</option>
                    <option value="link">Link</option>
                </select>
            </div>
            <div class="mb-2 content-data-field">
                <label>Data</label>
                    <textarea name="modules[${moduleIndex}][contents][${contentIndex}][data]" class="form-control content-data" required></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-content">Remove Content</button>
        </div>`;
        container.append(contentHtml);
    });
    $(document).on('change', 'select[name*="[type]"]', function () {
    const type = $(this).val();
    const contentBlock = $(this).closest('.content');
    const textarea = contentBlock.find('.content-data-field');

    if (type === 'image') {
        textarea.html(`
            <label>Upload Image</label>
            <input type="file" name="${$(this).attr('name').replace('[type]', '[data]')}" accept="image/*" class="form-control" required>
        `);
    } else {
        textarea.html(`
            <label>Data</label>
            <textarea name="${$(this).attr('name').replace('[type]', '[data]')}" class="form-control" required></textarea>
        `);
    }
});

    
</script>
</body>
</html>
