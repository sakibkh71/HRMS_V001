<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfInfo extends Model
{
	public $timestamps = false;
    protected $fillable = ['report_pdf_id', 'signatures', 'cover_head_text', 'page_header', 'page_footer'];
}
