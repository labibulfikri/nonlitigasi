<style>
    .pustaka-box {
        padding: 20px;
        font-family: sans-serif;
    }

    .search-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .search-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 2;
        min-width: 200px;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }

    .search-select {
        flex: 1;
        min-width: 140px;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
    }

    .search-btn {
        background: #4f46e5;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }

    .search-btn:hover {
        background: #4338ca;
    }

    .file-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .file-badge {
        font-size: 11px;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 12px;
        display: inline-block;
        margin-bottom: 8px;
    }

    .badge-nonlit {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-lampiran {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-asing {
        background: #fee2e2;
        color: #991b1b;
    }

    .file-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.3;
        word-break: break-word;
    }

    .file-name-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 10px;
        word-break: break-all;
    }

    .file-meta {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .file-actions {
        display: flex;
        gap: 8px;
        margin-top: 14px;
    }

    .btn-act {
        flex: 1;
        text-align: center;
        padding: 7px 10px;
        font-size: 12px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-preview {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        cursor: pointer;
    }

    .btn-download {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
</style>

<div class="pustaka-box">
    <h4 class="font-weight-bold text-dark mb-1"><i class="fa fa-folder-open"></i> Perpustakaan Berkas Terpadu</h4>
    <p class="text-muted small mb-4">Filter pencarian berdasarkan Sumber Kategori dan Kriteria Data.</p>

    <!-- Search Form with Filters -->
    <div class="search-card">
        <div class="search-group">
            <select id="select-kategori" class="search-select">
                <option value="ALL">-- Semua Sumber --</option>
                <option value="NONLIT">Non-Litigasi</option>
                <option value="LAMPIRAN">Lampiran Nonlit</option>
                <option value="ASING">Litigasi ASING</option>
            </select>

            <select id="select-kriteria" class="search-select">
                <option value="ALL">-- Cari Berdasarkan --</option>
                <option value="PIHAK">Nama Pihak (Penggugat/Tergugat/Pemohon)</option>
                <option value="NOMOR">Nomor Perkara / Register</option>
                <option value="MASALAH">Permasalahan / Objek / Amar</option>
                <option value="BERKAS">Judul / Nama File Berkas</option>
            </select>

            <input type="text" id="input-search" class="search-input" placeholder="Ketik kata kunci pencarian..." autocomplete="off">
            <button type="button" id="btn-search" class="search-btn"><i class="fa fa-search"></i> Cari</button>
        </div>
        <small class="text-muted mt-2 d-block"><i class="fa fa-info-circle"></i> Ketik minimal 3 karakter untuk melakukan pencarian instan.</small>
    </div>

    <!-- Spinner Loading -->
    <div id="loading-spinner" style="display:none;" class="text-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="text-muted small mt-2">Mencari berkas di database...</p>
    </div>

    <!-- Container Hasil -->
    <div id="container-hasil-berkas" class="row">
        <div class="col-12 text-center text-muted py-5">
            <i class="fa fa-search fa-3x mb-3 text-secondary"></i>
            <h6>Masukkan kata kunci pada kolom di atas untuk mencari berkas.</h6>
        </div>
    </div>
</div>

<!-- Modal PDF Preview -->
<div class="modal fade" id="modalPreviewPDF" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewTitle">Preview Berkas</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="pdfFrame" src="" style="width:100%; height:100%; border:none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    let searchTimer;

    $('#input-search').on('keyup', function() {
        clearTimeout(searchTimer);
        let keyword = $(this).val();

        if (keyword.length < 3) {
            $('#container-hasil-berkas').html(`
            <div class="col-12 text-center text-muted py-5">
                <i class="fa fa-search fa-3x mb-3 text-secondary"></i>
                <h6>Masukkan kata kunci pada kolom di atas untuk mencari berkas.</h6>
            </div>
        `);
            return;
        }

        searchTimer = setTimeout(function() {
            doSearch();
        }, 400);
    });

    $('#btn-search, #select-kategori, #select-kriteria').on('click change', function() {
        let keyword = $('#input-search').val();
        if (keyword.length >= 3) {
            doSearch();
        }
    });

    function doSearch() {
        let keyword = $('#input-search').val();
        let kategori = $('#select-kategori').val();
        let kriteria = $('#select-kriteria').val();

        if (keyword.length < 3) return;

        $('#loading-spinner').show();
        $('#container-hasil-berkas').hide();

        $.ajax({
            url: "<?= site_url($this->router->fetch_class() . '/ajax_search_pustaka'); ?>",
            type: "GET",
            data: {
                q: keyword,
                kategori: kategori,
                kriteria: kriteria
            },
            dataType: "json",
            success: function(res) {
                $('#loading-spinner').hide();
                $('#container-hasil-berkas').show();
                let html = '';

                if (res.data && res.data.length > 0) {
                    res.data.forEach(function(item) {
                        let badgeClass = 'badge-nonlit';
                        if (item.kategori === 'LAMPIRAN') badgeClass = 'badge-lampiran';
                        if (item.kategori === 'ASING') badgeClass = 'badge-asing';

                        let judulTampil = item.label_berkas ? item.label_berkas : item.nama_file;

                        html += `
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="file-card">
                                <div>
                                    <span class="file-badge ${badgeClass}">${item.kategori}</span>
                                    <div class="file-title" title="${judulTampil}">${judulTampil}</div>
                                    <div class="file-name-sub"><i class="fa fa-file-pdf-o text-danger"></i> ${item.nama_file}</div>
                                    <div class="file-meta"><strong>No. Reg:</strong> ${item.nomor_register || '-'}</div>
                                    <div class="file-meta"><strong>Pihak:</strong> ${item.nama_pihak || '-'}</div>
                                </div>
                                
                                <div class="file-actions">
                                    <button class="btn-act btn-preview" onclick="previewPDF('${item.file_path}', '${judulTampil.replace(/'/g, "\\'")}')">
                                        Preview
                                    </button>
                                    <a href="${item.file_path.startsWith('http') ? item.file_path : '<?= base_url(); ?>' + item.file_path}" download class="btn-act btn-download">
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    });
                } else {
                    html = `
                    <div class="col-12 text-center text-muted py-5">
                        <h6>Tidak ada berkas yang cocok dengan kata kunci "${keyword}".</h6>
                    </div>
                `;
                }
                $('#container-hasil-berkas').html(html);
            },
            error: function(xhr, status, error) {
                $('#loading-spinner').hide();
                $('#container-hasil-berkas').show().html(`
                <div class="col-12 text-center text-danger py-5">
                    <h6>Terjadi kesalahan saat mengambil data dari server.</h6>
                </div>
            `);
            }
        });
    }

    function previewPDF(filePath, fileName) {
        let fullUrl = filePath.startsWith('http') ? filePath : '<?= base_url(); ?>' + filePath;
        $('#previewTitle').text(fileName);
        $('#pdfFrame').attr('src', fullUrl);
        $('#modalPreviewPDF').modal('show');
    }
</script>