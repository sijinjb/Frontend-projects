<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice as ModelsInvoice;
use App\Models\InvoiceItems;
use App\Models\Products;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Invoice extends Controller
{
    /**
     * Create new invoice :: 
     * Create invoice initial details. Default status will be Draft
     **/
    public function create(Request $request)
    {
        $data['user'] = Auth::user();

        if ($request->isMethod('post')) {
            $request->validate([
                'generated_date' => 'required',
                'due_date' => 'required',
                'client' => 'required|numeric',
                'type' => 'required|in:Delivery,Payment'
            ]);

            $invoice = new ModelsInvoice();
            $invoice->generated_date = $request->generated_date;
            $invoice->client = $request->client;
            $invoice->type = $request->type;
            $invoice->due_date = $request->due_date;
            $invoice->total_amount = 0;
            $invoice->total_gst = 0;
            $invoice->payable = 0;
            $invoice->status = 'Draft';
            $invoice->comments = json_encode([
                [
                    'message' => 'Created invoice draft',
                    'ts' => date('d-m-Y h:i s', strtotime('+ 5 hours 30 minutes')),
                    'by' => $data['user']->id
                ]
            ]);
            $invoice->created_by = $data['user']->id;
            $invoice->updated_by = $data['user']->id;

            if ($invoice->save()) {
                return redirect()->route('app.invoice.add.particulars', ['uuid' => $invoice->uuid])->with('success', 'Invoice draft generated successfully');
            }

            $data['error'] = 'Failed to create invoice draft. Please try again';
        }

        $data['clients'] = Client::get();
        return view('invoices.create', $data);
    }

    /**
     * Add invoice particular :: Only invoice in draft state allowed
     *
     **/
    public function addParticulars(Request $request, string $uuid)
    {
        $data['invoice'] = ModelsInvoice::where('uuid', '=', $uuid)->where('status', '=', 'Draft')->first();
        $data['user'] = Auth::user();
        $data['products'] = Products::get()->toArray();

        if (!$data['invoice']) {
            abort(403, 'Unauthorized access');
        }

        if ($request->isMethod('post')) {
            try {
                $response = $this->formatParticulars($request->product, $request->quantity, $data);
                $data['error'] = "Invalid particulars details";
                if ($response['items']) {
                    InvoiceItems::insert($response['items']);
                    $data['invoice']->total_amount = $response['total']['amount'];
                    $data['invoice']->total_gst = $response['total']['gst'];
                    $data['invoice']->payable = $response['total']['payable'];
                    $data['invoice']->status = 'Pending';
                    $comments = json_decode($data['invoice']->comments, true);
                    $comments[] = [
                        'message' => 'Added invoice particulars and updated status to pending',
                        'ts' => date('d-m-Y h:i s', strtotime('+ 5 hours 30 minutes')),
                        'by' => $data['user']->id
                    ];
                    $data['invoice']->comments = json_encode($comments);
                    if ($data['invoice']->save()) {
                        return redirect()->route('app.invoice.list')->with('success', 'Invoice draft generated successfully');
                    }
                    $data['error'] = "Failed to save invoice particulars";
                }
            } catch (\Throwable $th) {
                Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
                $data['error'] = "Failed to save invoice particulars." . $th->getMessage();
            }
        }

        return view('invoices.create-particulars', $data);
    }

    /**
     * Display all invoices
     **/
    public function list(Request $request)
    {
        $data['user'] = Auth::user();
        $limit = $request->has($request->limit) && $request->limit > 0 ? $request->limit * 25 : 25;
        $offset = $request->has($request->limit) && $request->limit > 0 ? $limit - 1 : 0;
        if ($data['user']->role == "cashier") {
            $invoices = ModelsInvoice::where("invoices_master.created_by", "=", $data['user']->id)
                ->leftJoin("clients as c", "c.id", "=", "invoices_master.client")
                ->leftJoin("users as cu", "cu.id", "=", "invoices_master.created_by")
                ->leftJoin("users as uu", "uu.id", "=", "invoices_master.updated_by")
                ->leftJoin("users as mu", "mu.id", "=", "invoices_master.manager_id")
                ->select([
                    "invoices_master.*",
                    "cu.name as created_name",
                    "uu.name as updated_name",
                    "mu.name as manager_name",
                    "c.name as client_name"
                ])
                ->limit($limit)
                ->offset($offset)
                ->orderBy("updated_at", "desc")
                ->get();
        } else if ($data['user']->role == "manager") {
            $invoices = ModelsInvoice::where("invoices_master.manager_id", "=", $data['user']->id)
                ->leftJoin("clients as c", "c.id", "=", "invoices_master.client")
                ->leftJoin("users as cu", "cu.id", "=", "invoices_master.created_by")
                ->leftJoin("users as uu", "uu.id", "=", "invoices_master.updated_by")
                ->leftJoin("users as mu", "mu.id", "=", "invoices_master.manager_id")
                ->select([
                    "invoices_master.*",
                    "cu.name as created_name",
                    "uu.name as updated_name",
                    "mu.name as manager_name",
                    "c.name as client_name"
                ])
                ->where('status', '!=', 'Draft')
                ->limit($limit)
                ->offset($offset)
                ->orderBy("updated_at", "desc")
                ->get();
        } else {
            $invoices = ModelsInvoice::where('status', '!=', 'Draft')
                ->leftJoin("clients as c", "c.id", "=", "invoices_master.client")
                ->leftJoin("users as cu", "cu.id", "=", "invoices_master.created_by")
                ->leftJoin("users as uu", "uu.id", "=", "invoices_master.updated_by")
                ->leftJoin("users as mu", "mu.id", "=", "invoices_master.manager_id")
                ->select([
                    "invoices_master.*",
                    "cu.name as created_name",
                    "uu.name as updated_name",
                    "mu.name as manager_name",
                    "c.name as client_name"
                ])
                ->limit($limit)
                ->offset($offset)
                ->orderBy("updated_at", "desc")
                ->get();
            $managers = User::where('role', '=', 'manager')
                ->where('active', '=', 1)
                ->get();
            $data['managers_partial'] = view('invoices.partial-manage-admin', ['managers' => $managers])->render();
        }
        $data['invoices'] = $invoices;
        return view('invoices.list', $data);
    }

    /**
     * Create Invoice PDF
     **/
    public function invoicePdf(Request $request, string $uuid = "")
    {
        if ($uuid == "") {
            return back()->withErrors(['denied' => 'Access Denied. Invalid invoice id']);
        }
        $data['invoice'] = ModelsInvoice::where("uuid", "=", $uuid)->where("status", "!=", "draft")->first();
        if (!$data['invoice']) {
            return back()->withErrors(['denied' => 'Access Denied. Invoice not valid']);
        }
        $data['items'] = InvoiceItems::where("invoice_id", "=", $data['invoice']->id)->get();
        $data['user'] = User::where("id", "=", $data['invoice']->created_by)->first();
        $data['userDetails'] = json_decode(Cache::get("user_details_{$data['user']->id}"));
        // return view("invoices.invoice-pdf", $data);
        $pdf = PDF::loadView("invoices.invoice-pdf", $data);
        $pdf->setPaper('A4');
        if($request->has("download")) {
            return $pdf->download("invoice-$uuid.pdf");
        }
        return $pdf->stream("invoice-$uuid.pdf");
    }

    /**
     * Ajax Call. Get invoice particular as html or json
     * 
     * @return JsonResponse
     **/
    public function getParticulars(Request $request, int $invoice_id)
    {
        try {
            $data['items'] = InvoiceItems::where("invoice_id", '=', $invoice_id)
                ->select([
                    "invoices_particulars.*",
                    "cu.name as created_by_name",
                    "uu.name as updated_by_name",
                    "p.name as product_name"
                ])
                ->leftJoin("users as cu", "invoices_particulars.created_by", "=", "cu.id")
                ->leftJoin("users as uu", "invoices_particulars.updated_by", "=", "uu.id")
                ->leftJoin("products as p", "invoices_particulars.product_id", "=", "p.id")
                ->get()->toArray();
            if ($request->has('type') && $request->type == "html") {
                return view('invoices.partial-particulars', $data);
            }
            return response()->json($data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            $data['error'] = "Failed to load invoice particulars." . $th->getMessage();
            return response()->json($data, 409);
        }
    }

    /**
     * Format invoice particulars (items) and calculate total amounts
     * 
     * @param Array $products
     * @param Array $quantity
     * @param Array $data contains invoice, products and user details
     * 
     * @return Array
     **/
    private function formatParticulars(array $products, array $quantity, array $data): array
    {
        $response = [];
        $total = [
            'amount' => 0,
            'payable' => 0,
            'gst' => 0
        ];
        // dd($products , $data['products'] , $quantity);
        foreach ($data['products'] as $product) {
            $index = array_search($product['id'], $products);
            if ($index !== false && isset($quantity[$index])) {
                $amount = $product['price'] * $quantity[$index];
                $gst = ($product['gst_percent'] / 100) * $amount;
                $netAmount = $amount + $gst;
                $total['amount'] += $amount;
                $total['payable'] += $netAmount;
                $total['gst'] += $gst;

                $response[] = [
                    'product_id' => $product['id'],
                    'price' => $product['price'],
                    'quantity' => $quantity[$index],
                    'amount' => $amount,
                    'gst_percent' => $product['gst_percent'],
                    'gst' => $gst,
                    'total' => $netAmount,
                    'invoice_id' => $data['invoice']->id,
                    'created_by' => $data['user']->id,
                    'updated_by' => $data['user']->id,
                    'comments' => json_encode([
                        [
                            'message' => "{$product['name']}({$product['id']}) added to invoice {$data['invoice']->uuid}",
                            'ts' => date('d-m-Y h:i s', strtotime('+ 5 hours 30 minutes')),
                            'by' => $data['user']->id
                        ]
                    ])
                ];
            }
        }

        return ['items' => $response, 'total' => $total];
    }
}
