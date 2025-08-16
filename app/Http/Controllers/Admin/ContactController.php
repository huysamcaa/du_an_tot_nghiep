<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
   public function index() {
    $contacts = Contact::orderBy('created_at', 'desc')->get();
    return view('admin.contact.index', compact('contacts'));
}
public function show($id) {
        $contact = Contact::findOrFail($id);
        return view('admin.contact.show', compact('contact'));
    }
    
    public function markContacted($id)
{
    $contact = Contact::findOrFail($id);
    $contact->is_contacted = true;
    $contact->save();

    return redirect()->route('admin.contact.index')->with('success', 'Đã đánh dấu là đã liên hệ.');
}
}
