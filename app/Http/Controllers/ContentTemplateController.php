<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use App\Helpers\CustomHelper;
use App\Models\ContentTemplate;

class ContentTemplateController extends Controller
{

    public function index()
    {
        try
            {
                $templates = ContentTemplate::all();
                $title = "Content Template | " . CustomHelper::Get_website_name();
                return view('content_templates.index', compact('templates','title'));
            }
        catch(\Throwable $th)
            {
                return view('layouts.404-Page');
            }
    }

    public function create()
    {
        try
            {
                $title = "Content Template | " . CustomHelper::Get_website_name();
                return view('content_templates.create', compact('title'));
            }
        catch(\Throwable $th)
            {
                return view('layouts.404-Page');
            }
    }

    public function store(Request $request)
    {
        try
            {
                $request->validate([
                    'template_type' => 'required|string|max:255',
                    'template_subject' => 'required|string|max:255',
                    'template_content' => 'required',
                    'role_type' => 'nullable|string|max:255',
                ]);

                ContentTemplate::create($request->all());
                return redirect()->route('template.index')->with('success', 'Content Template created successfully.');
            }
        catch(\Throwable $th)
            {
                return view('layouts.404-Page');
            }
    }


    public function edit($id)
    {
        try
            {
                $template = ContentTemplate::findOrFail($id);
                $title = "Content Template | " . CustomHelper::Get_website_name();
                return view('content_templates.edit', compact('template','title'));
            }
        catch(\Throwable $th)
            {
                return view('layouts.404-Page');
            }
    }

    public function update(Request $request, $id)
    {
        try
            {
                $request->validate([
                    'template_type' => 'required|string|max:255',
                    'template_subject' => 'required|string|max:255',
                    'template_content' => 'required',
                    'role_type' => 'nullable|string|max:255',
                ]);

                $template = ContentTemplate::findOrFail($id);
                $template->update($request->all());
                return redirect()->route('template.index')->with('success', 'Content Template updated successfully.');
            }
        catch(\Throwable $th)
            {
                return view('layouts.404-Page');
                // return $th;
            }
    }


    public function destroy($id)
    {
        try
            {
                $template = ContentTemplate::findOrFail($id);
                $template->delete();
                return redirect()->route('template.index')->with('success', 'Content Template deleted successfully.');
            }
        catch(\Throwable $th)
            {
                return view('layouts.404-Page');
            }
    }


}