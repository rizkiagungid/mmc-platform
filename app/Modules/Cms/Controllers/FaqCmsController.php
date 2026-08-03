<?php

namespace App\Modules\Cms\Controllers;

use App\Controllers\BaseController;

class FaqCmsController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $faqs = $db->table('faqs')->orderBy('sort_order', 'ASC')->orderBy('id', 'ASC')->get()->getResultArray();

        return view('App\Modules\Cms\Views\faqs\index', [
            'title' => 'Manajemen FAQ (Frequently Asked Questions)',
            'faqs'  => $faqs,
        ]);
    }

    public function store()
    {
        $db       = \Config\Database::connect();
        $question = trim($this->request->getPost('question') ?? '');
        $answer   = trim($this->request->getPost('answer') ?? '');
        $sort     = (int)($this->request->getPost('sort_order') ?? 0);
        $status   = $this->request->getPost('status') ?: 'active';

        if (empty($question) || empty($answer)) {
            return redirect()->back()->with('error', 'Pertanyaan dan Jawaban wajib diisi.');
        }

        $db->table('faqs')->insert([
            'question'   => $question,
            'answer'     => $answer,
            'sort_order' => $sort,
            'status'     => $status,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/faqs')->with('success', 'Pertanyaan FAQ baru berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $db       = \Config\Database::connect();
        $question = trim($this->request->getPost('question') ?? '');
        $answer   = trim($this->request->getPost('answer') ?? '');
        $sort     = (int)($this->request->getPost('sort_order') ?? 0);
        $status   = $this->request->getPost('status') ?: 'active';

        if (empty($question) || empty($answer)) {
            return redirect()->back()->with('error', 'Pertanyaan dan Jawaban wajib diisi.');
        }

        $db->table('faqs')->where('id', $id)->update([
            'question'   => $question,
            'answer'     => $answer,
            'sort_order' => $sort,
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/cms/faqs')->with('success', 'Data FAQ berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $db = \Config\Database::connect();
        $db->table('faqs')->where('id', $id)->delete();
        return redirect()->to('/admin/cms/faqs')->with('success', 'FAQ berhasil dihapus.');
    }
}
