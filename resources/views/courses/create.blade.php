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
        .content { background-color: #f9f9f9; margin-left: 20px; }

        .course-card {
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <!-- Left Side: Form -->
        <div class="col-md-8">
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

                <hr><h4>Modules</h4>
                <div id="modules-container"></div>
                <button type="button" class="btn btn-secondary" id="add-module">Add Module</button>
                <br><br>
                <button type="submit" class="btn btn-success">Save Course</button>
            </form>
        </div>

        <!-- Right Side: Course List -->
        <!-- Right Side: Course List in Table Format -->
<div class="col-md-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Course List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Course</th>
                            <th>Category</th>
                            <th>Modules</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $id => $course)
                            <tr>
                                <td><strong>{{ $course['title'] }}</strong></td>
                                <td>{{ $course['category'] ?? 'N/A' }}</td>
                                <td>
                                    @if (!empty($course['modules']))
                                        <ul class="ps-3 mb-0">
                                            @foreach ($course['modules'] as $module)
                                                <li>{{ $module['title'] }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">No modules</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger delete-course" data-id="{{ $id }}">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No courses yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- JS Script Section -->
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
                <button type="button" class="btn btn-sm btn-danger remove-module mt-2">Remove Module ❌</button>
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
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                    <option value="link">Link</option>
                </select>
            </div>
            <div class="mb-2 content-data-field">
                <label>Data</label>
                <textarea name="modules[${moduleIndex}][contents][${contentIndex}][data]" class="form-control" required></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-content">Remove Content</button>
        </div>`;
        container.append(contentHtml);
    });

    $(document).on('change', 'select[name*="[type]"]', function () {
        const type = $(this).val();
        const parent = $(this).closest('.content');
        const dataName = $(this).attr('name').replace('[type]', '[data]');

        const html = (type === 'image')
            ? `<label>Upload Image</label><input type="file" name="${dataName}" class="form-control" required>`
            : `<label>Data</label><textarea name="${dataName}" class="form-control" required></textarea>`;

        parent.find('.content-data-field').html(html);
    });

    $(document).on('click', '.delete-course', function () {
        const id = $(this).data('id');
        if (!confirm('Are you sure you want to delete this course?')) return;

        $.ajax({
            url: `/courses/delete/${id}`,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                location.reload();
            },
            error: function () {
                alert('Failed to delete course.');
            }
        });
    });
</script>
</body>
</html>
