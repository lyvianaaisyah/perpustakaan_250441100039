const API = '/api';
const FIELD = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];
const $ = (id) => document.getElementById(id);

const esc = (t) =>
    String(t ?? '').replace(/[&<>"']/g, (c) =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])
    );

class ApiError extends Error {
    constructor(status, message, errors = {}) {
        super(message);
        this.status = status;
        this.errors = errors;
    }
}

const pesan = (teks, jenis = 'success') => {
    if ($('alert-area')) {
        $('alert-area').innerHTML = `<div class="alert-box alert-${jenis}">${esc(teks)}</div>`;
    }
};

async function apiRequest(path, { method = 'GET', body = null } = {}) {
    const headers = { Accept: 'application/json' };
    if (body) headers['Content-Type'] = 'application/json';

    let res;
    try {
        res = await fetch(API + path, {
            method,
            headers,
            body: body ? JSON.stringify(body) : undefined,
        });
    } catch {
        throw new ApiError(0, 'Tidak dapat terhubung ke server.');
    }

    if (res.status === 204) return null;

    const data = await res.json().catch(() => null);

    if (!res.ok) {
        throw new ApiError(res.status, data?.message ?? `HTTP Error ${res.status}`, data?.errors ?? {});
    }

    return data;
}

function tanganiError(err) {
    FIELD.forEach((f) => { if ($(`err-${f}`)) $(`err-${f}`).textContent = ''; });

    if (err.status === 422) {
        Object.entries(err.errors).forEach(([f, m]) => {
            if ($(`err-${f}`)) $(`err-${f}`).textContent = m.join(' ');
        });
        pesan('Data belum valid. Periksa kembali isian formulir.', 'warning');
    } else if (err.status === 404) {
        pesan('Data buku tidak ditemukan.', 'warning');
        muatBuku();
    } else {
        pesan(err.message, 'danger');
    }
}

async function muatBuku() {
    try {
        const json = await apiRequest('/buku');
        const daftar = Array.isArray(json.data) ? json.data : (json.data?.data ?? []);
        const bisaKelola = Boolean(window.BISA_KELOLA_BUKU);

        $('tbody-buku').innerHTML = daftar.map((buku, i) => {
            const namaKategori = typeof buku.kategori === 'object'
                ? (buku.kategori?.nama ?? '-')
                : (buku.kategori ?? '-');
            const judulBuku = buku.judul_buku ?? buku.judul ?? '-';
            const namaPenulis = buku.nama_penulis ?? buku.penulis ?? '-';
            const tahunTerbit = buku.tahun ?? buku.tahun_terbit ?? '-';

            const tombolAksi = bisaKelola
                ? `<button type="button" class="btn-aksi btn-ubah" data-aksi="ubah" data-id="${buku.id}">Ubah</button>
                   <button type="button" class="btn-aksi btn-hapus" data-aksi="hapus" data-id="${buku.id}">Hapus</button>`
                : `<a href="/buku/${buku.id}" class="tombol" style="margin-top:0; padding:4px 10px; font-size:12px;">Detail</a>`;

            return `
                <tr>
                    <td>${i + 1}</td>
                    <td><span class="badge-kat">${esc(namaKategori)}</span></td>
                    <td><b>${esc(judulBuku)}</b></td>
                    <td>${esc(namaPenulis)}</td>
                    <td>${esc(tahunTerbit)}</td>
                    <td>${tombolAksi}</td>
                </tr>
            `;
        }).join('') || '<tr><td colspan="6" style="text-align:center;">Belum ada data buku.</td></tr>';
    } catch (err) {
        tanganiError(err);
    }
}

function isiForm(buku = {}) {
    if (!$('form-buku')) return;
    FIELD.forEach((f) => { if ($(`err-${f}`)) $(`err-${f}`).textContent = ''; });
    $('buku-id').value = buku.id ?? '';
    $('buku-kategori_id').value = buku.kategori_id ?? buku.kategori?.id ?? '';
    $('buku-judul').value = buku.judul_buku ?? buku.judul ?? '';
    $('buku-penulis').value = buku.nama_penulis ?? buku.penulis ?? '';
    $('buku-tahun_terbit').value = buku.tahun ?? buku.tahun_terbit ?? '';

    $('form-title').textContent = buku.id ? `Ubah Buku (ID: ${buku.id})` : 'Tambah Buku Baru';
    $('btn-simpan').textContent = buku.id ? 'Perbarui Data' : 'Simpan Buku';
    $('btn-batal').style.display = buku.id ? 'inline-block' : 'none';
}

async function simpan(e) {
    e.preventDefault();
    const id = $('buku-id').value;
    const body = {
        kategori_id: $('buku-kategori_id').value || null,
        judul: $('buku-judul').value.trim(),
        penulis: $('buku-penulis').value.trim(),
        tahun_terbit: $('buku-tahun_terbit').value === '' ? null : Number($('buku-tahun_terbit').value),
    };

    try {
        await apiRequest(id ? `/buku/${id}` : '/buku', {
            method: id ? 'PUT' : 'POST',
            body,
        });

        pesan(id ? 'Data buku berhasil diperbarui.' : 'Data buku baru berhasil ditambahkan.');
        isiForm();
        await muatBuku();
    } catch (err) {
        tanganiError(err);
    }
}

async function bukaFormUbah(id) {
    try {
        const res = await apiRequest(`/buku/${id}`);
        isiForm(res.data);
    } catch (err) {
        tanganiError(err);
    }
}

async function hapus(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

    try {
        await apiRequest(`/buku/${id}`, { method: 'DELETE' });
        pesan('Data buku berhasil dihapus.');
        await muatBuku();
    } catch (err) {
        tanganiError(err);
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    if ($('form-buku')) $('form-buku').onsubmit = simpan;
    if ($('btn-batal')) $('btn-batal').onclick = () => isiForm();

    if ($('tbody-buku')) {
        $('tbody-buku').onclick = (e) => {
            const b = e.target.closest('button[data-aksi]');
            if (!b) return;
            if (b.dataset.aksi === 'hapus') hapus(b.dataset.id);
            else bukaFormUbah(b.dataset.id);
        };
    }

    await muatBuku();
});