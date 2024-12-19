<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Menampilkan daftar tugas dengan filter status
    public function index(Request $request)
    {
        // $status = $request->input('status', 'To Do'); // Default status adalah 'To Do'
        $status = $request->query('status', 'Semua Status'); // Default ke "Semua Status"
        $tasks = Task::where('status', $status)->get();
        $divisi = $request->query('divisi', null);
        
         // Query tugas berdasarkan filter status dan divisi
    $query = Task::query();

    if ($status && $status != 'Semua Status') {
        $query->where('status', $status);
    }

    if ($divisi) {
        $query->where('divisi_id', $divisi);
    }

    // Ambil hasil query
    $tasks = $query->get();

    // Passing data ke view
    return view('tasks.index', [
        'tasks' => $tasks,
        'status' => $status, // Passing status ke view
        'divisi' => $divisi  // Passing divisi ke view
    ]);

        // // 
        // $divisi = $request->query('divisi', null);
        // // 

        // return view('tasks.index', compact('tasks', 'status'));
    }

    // Menampilkan form untuk menambahkan tugas
    public function create()
    {

        $users = User::all();


        return view('tasks.create', compact('users'));
    }

    public function edit($id)
    {
        $task = Task::where('id', $id)->first();
        $users = User::all();


        return view('tasks.edit', compact('task','users'));
    }

    public function destroy($task)
{
    // Find the task by its ID
    $task = Task::findOrFail($task);

    // Delete the task
    $task->delete();

    // Redirect back with a success message
    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
}


    // Menyimpan tugas baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
            'due_date' => 'required|date',
        ]);

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filePath = $file->store('attachments', 'public'); // Simpan ke folder `attachments`
        } else {
     
        }
        

        Task::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'attachment' => $attachmentPath,
            'due_date' => $request->due_date,
            'divisi_id' => $request->divisi, // Ini bisa diubah sesuai kebutuhan
            'status' => 'To Do',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }



    // update status
    public function approve(Request $request, $taskId)
{
    $task = Task::findOrFail($taskId);
    $task->status = 'Approve'; // Mengubah status menjadi done
    $task->save();

    return redirect()->back()->with('success', 'Task approved successfully!');
}

public function reject(Request $request, $taskId)
{
    $task = Task::findOrFail($taskId);
    $task->status = 'Reject'; // Mengubah status menjadi done meskipun ditolak
    $task->save();

    return redirect()->back()->with('success', 'Task rejected successfully!');
}

public function revision(Request $request, $taskId)
{
    $task = Task::findOrFail($taskId);
    $task->status = 'To Do'; // Mengubah status menjadi done meskipun ditolak
    $task->save();

    return redirect()->back()->with('success', 'Task rejected successfully!');
}




}
