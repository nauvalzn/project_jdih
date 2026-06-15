<!DOCTYPE html>
<html>
<head>
    <title>Edit Dokumen</title>
    <script src="{{ $onlyOfficeServer }}/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
    <div id="editor" style="width: 100%; height: 90vh;"></div>

    <script>
        const docEditor = new DocsAPI.DocEditor("editor", {
            document: {
                fileType: '{{ pathinfo($filename, PATHINFO_EXTENSION) }}',
                key: '{{ $documentKey }}',
                title: '{{ $filename }}',
                url: '{{ $fileUrl }}', // ✅ ini harus bisa diakses dari browser dan OnlyOffice
                permissions: {
                    edit: true,
                    download: true,
                },
            },
            documentType: 'word',
            editorConfig: {
                mode: 'edit',
                lang: 'id',
                user: {
                    id: '1',
                    name: 'Admin Laravel',
                }
            },
            height: "100%",
            width: "100%"
        });
    </script>
</body>
</html>
