import re

with open('resources/views/hak_akses/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Add CSS at the top inside @section('content')
css_style = """
<style>
    .table-responsive {
        max-height: 600px;
    }
    .vertical-header {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        vertical-align: middle !important;
        text-align: left;
    }
    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: #fff !important;
        border-right: 2px solid #dee2e6;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }
    .sticky-header {
        position: sticky;
        left: 0;
        z-index: 3;
        background-color: #212529 !important;
        color: #fff;
        border-right: 2px solid #dee2e6;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        vertical-align: middle !important;
    }
</style>
<div class="row justify-content-center">"""

content = content.replace('<div class="row justify-content-center">', css_style)

# Replace table class (remove text-nowrap from table to allow vertical headers)
content = content.replace(
    '<table class="table table-bordered table-striped table-hover align-middle mb-0 text-nowrap">',
    '<table class="table table-bordered table-hover align-middle mb-0">'
)

# Replace th for Level/Role
content = content.replace(
    '<th style="width: 25%;">Level / Role</th>',
    '<th class="sticky-header" style="min-width: 150px;">Level / Role</th>'
)

# Replace th for menus
content = content.replace(
    '<th class="text-center" style="font-size: 0.75rem; padding: 12px 6px !important;">',
    '<th class="text-center vertical-header" style="font-size: 0.75rem; padding: 10px 5px !important; width: 40px;">'
)

# Replace td for role
content = content.replace(
    '<td class="fw-bold text-dark text-capitalize py-3">',
    '<td class="fw-bold text-dark text-capitalize py-3 sticky-col">'
)

with open('resources/views/hak_akses/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
