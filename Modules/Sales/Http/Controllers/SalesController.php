<?php

namespace Modules\Sales\Http\Controllers;

use App\Rules\FromToDateRule;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\DeliveryOrder;
use Illuminate\Contracts\Support\Renderable;
use Modules\HR\Entities\Employee;
use Modules\Sales\Entities\Customer;
use Modules\Sales\Entities\DeliveryChallan;
use Modules\Sales\Entities\DeliveryChallanDetail;
use Modules\Sales\Entities\deliveryOrderDetail;
use Modules\Sales\Entities\SalesReturn;
use Modules\Sales\Entities\SalesReturnDetail;
use Modules\Sales\Entities\SalesZone;
use Modules\SupplyChain\Entities\Category;
use Modules\SupplyChain\Entities\Product;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('sales::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('sales::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('sales::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('sales::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function deliverySalesCollection()
    {
        $formType = 'delivery-sales-collection';
        $categories = Category::select('categories.type_id', 'categories.id', 'categories.name AS category_name', 'product_types.name AS type_name')
            ->join('product_types', 'categories.type_id', '=', 'product_types.id')
            ->orderBy('categories.type_id')
            ->orderBy('categories.name')
            ->get()
            ->groupBy('type_id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $products = Product::orderBy('name')->pluck('name', 'id');
        $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');
        // $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::delivery-sales-collections-report.index', compact('formType', 'categories', 'customers', 'products', 'zones', 'employees'));
    }

    public function deliverySalesCollectionReport(Request $request)
    {

        $request->validate([
            'from_date' => 'required',
            'to_date' => ['required',  new FromToDateRule()]
        ]);


        // $pdf = PDF::loadView('sales::delivery-sales-collections-report.print',  [], [
        //     'title' => 'Certificate',
        //     'format' => 'A4-L',
        //     'orientation' => 'L'
        // ]);
        // $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        // $pdf->getMpdf()->showWatermarkText = true;
        // return $pdf->stream('Certificate.pdf');


        // $reportData = DeliveryOrder::with('deliveryOrderDetails', 'deliveryOrderDetails.product', 'deliveryOrderDetails.product.unit', 'customer')
        //     ->whereBetween('date', [$request->from_date, $request->to_date])
        //     ->get()->groupBy('date');

        //$deliveryOrders = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer')->orderBy('id', 'desc')->get();
        $productId = $request->product_id;
        $categoryId = $request->category_id;
        $zoneId = $request->zone_id;
        $customerId = $request->customer_id ;
        $employeeId = $request->employee_id;
        $from_date = $request->from_date ;
        $to_date = $request->to_date ;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Zone" => $zoneId != null ? SalesZone::find($zoneId)?->zone : 'All',
            "Category" => $categoryId != null ? Category::find($categoryId)?->name : 'All',
            "Product" => $productId != null ? Product::find($productId)?->name : 'All',
            "Customer" => $customerId != null ? Customer::find($customerId)?->name : 'All',
            "Executive" => $employeeId != null ? Employee::find($employeeId)?->emp_name : 'All',
        ];
// dd($categoryId);
        if($categoryId == null){
            $reportData = DeliveryOrder::leftJoin('delivery_order_details', 'delivery_orders.id', '=', 'delivery_order_details.delivery_order_id')
            ->leftJoin('products', 'delivery_order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('customers', 'customers.id', '=', 'delivery_orders.customer_id')
            ->leftJoin('sales_zones', 'sales_zones.id', '=', 'delivery_orders.zone_id')
            ->leftJoin('employees', 'employees.id', '=', 'delivery_orders.employee_id')
            ->select('delivery_orders.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('sales_zones.id', $zoneId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('employees.id', $employeeId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customers.id', $customerId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('delivery_orders.date', [$from_date, $to_date]);
            })
            // ->with(['deliveryOrderDetails' => function($query) use($categoryId, $productId){
            //     $query->when($productId, function($query2) use($productId){
            //         $query2->where('product_id', $productId);
            //     })->when($categoryId && !$productId, function($query2) use($categoryId){
            //         $query2->leftJoin('products', 'product_id', 'products.id')->where('products.category_id', $categoryId);
            //     });
            // }])
            ->with('deliveryOrderDetails', 'employee', 'customer','zone')
            ->groupBy('delivery_orders.id')
            ->orderBy('delivery_orders.id', 'desc')
            ->get();
        }
        else{
            $deliveryOrderDetails = deliveryOrderDetail::rightJoin('delivery_orders', 'delivery_orders.id', '=', 'delivery_order_details.delivery_order_id')
            ->leftJoin('products', 'delivery_order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('customers', 'customers.id', '=', 'delivery_orders.customer_id')
            ->leftJoin('sales_zones', 'sales_zones.id', '=', 'delivery_orders.zone_id')
            ->leftJoin('employees', 'employees.id', '=', 'delivery_orders.employee_id')
            ->select('delivery_order_details.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('sales_zones.id', $zoneId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('employees.id', $employeeId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customers.id', $customerId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('delivery_orders.date', [$from_date, $to_date]);
            })
            // ->with(['deliveryOrderDetails' => function($query) use($categoryId, $productId){
            //     $query->when($productId, function($query2) use($productId){
            //         $query2->where('product_id', $productId);
            //     })->when($categoryId && !$productId, function($query2) use($categoryId){
            //         $query2->leftJoin('products', 'product_id', 'products.id')->where('products.category_id', $categoryId);
            //     });
            // }])
            // ->groupBy('delivery_order_details.id')
            ->with('deliveryOrder', 'product' , 'deliveryOrder.customer', 'deliveryOrder.employee', 'deliveryOrder.zone')
            ->orderBy('delivery_orders.id', 'desc')
            ->get();

            $reportData = $deliveryOrderDetails->mapToGroups(function ($item, $key) {
                return [$item->product_id => $item];
            });

        }




        $pdf = PDF::loadView(
            $categoryId == null && $productId == null ? 'sales::delivery-sales-collections-report.print' : 'sales::delivery-sales-collections-report.category_product_wise_print',
            compact('reportData', 'print_date_time', 'from_date', 'to_date', 'search_criteria'),
            [],
            [
                'title' => 'Sales Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('sales_report.pdf');
    }

    public function deliverySalesCollectionEmployeeTarget(){
        $formType = 'delivery-sales-collection-employee-target';
        return view('sales::delivery-sales-collections-report.employee_target_index', compact('formType'));
    }

    public function deliverySalesCollectionEmployeeTargetReport(Request $request)
    {
        $request->validate([
            'month' => 'required',
        ]);


        // $pdf = PDF::loadView('sales::delivery-sales-collections-report.print',  [], [
        //     'title' => 'Certificate',
        //     'format' => 'A4-L',
        //     'orientation' => 'L'
        // ]);
        // $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        // $pdf->getMpdf()->showWatermarkText = true;
        // return $pdf->stream('Certificate.pdf');


        // $reportData = DeliveryOrder::with('deliveryOrderDetails', 'deliveryOrderDetails.product', 'deliveryOrderDetails.product.unit', 'customer')
        //     ->whereBetween('date', [$request->from_date, $request->to_date])
        //     ->get()->groupBy('date');

        //$deliveryOrders = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer')->orderBy('id', 'desc')->get();
        // $productId = $request->product_id;
        // $categoryId = $request->category_id;
        // $zoneId = $request->zone_id;
        // $customerId = $request->customer_id ;
        // $from_date = $request->from_date ;
        // $to_date = $request->to_date ;
        $month = $request->month;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');


// dd($categoryId);
        $employee_target_report_data = Employee::select('employees.id AS emp_id', 'employees.emp_name', 'employees.emp_code')
        ->selectRaw("sales_person_targets.month AS target_month")
        ->selectRaw('COALESCE(SUM(sales_person_target_details.target), 0) AS monthly_target')
        ->selectRaw('COALESCE(SUM(delivery_orders.total), 0) AS monthly_sale')
        ->selectRaw('COALESCE(SUM(delivery_orders.paid), 0) AS monthly_collection')
        ->leftJoin('sales_person_target_details', 'sales_person_target_details.employee_id', '=', 'employees.id')
        ->leftJoin('sales_person_targets', 'sales_person_targets.id', '=', 'sales_person_target_details.sales_person_target_id')
        ->leftJoin('delivery_orders', function ($join) use ($month) {
            $join->on('delivery_orders.employee_id', '=', 'employees.id')
                ->whereRaw("DATE_FORMAT(delivery_orders.date, '%Y-%m') = ?", $month);
        })
        ->where('sales_person_targets.month', '=', $month)
        ->groupBy('employees.id', 'sales_person_targets.id')
        ->get();



        $pdf = PDF::loadView('sales::delivery-sales-collections-report.employee_target_report_print',
            compact('employee_target_report_data', 'print_date_time', 'month'),
            [],
            [
                'title' => 'Employee Target Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('employee_target_sales_report.pdf');
    }


    public function salesReturn(){
        $formType = 'sales-return';
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $products = Product::orderBy('name')->pluck('name', 'id');
        $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::sales-return-report.index', compact('formType', 'categories', 'customers', 'products', 'zones', 'employees'));
    }

    public function salesReturnReport(Request $request){
        $request->validate([
            'from_date' => 'required',
            'to_date' => ['required',  new FromToDateRule()]
        ]);

        $productId = $request->product_id;
        $categoryId = $request->category_id;
        $zoneId = $request->zone_id;
        $customerId = $request->customer_id ;
        $employeeId = $request->employee_id;
        $from_date = $request->from_date ;
        $to_date = $request->to_date ;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Zone" => $zoneId != null ? SalesZone::find($zoneId)?->zone : 'All',
            "Category" => $categoryId != null ? Category::find($categoryId)?->name : 'All',
            "Product" => $productId != null ? Product::find($productId)?->name : 'All',
            "Customer" => $customerId != null ? Customer::find($customerId)?->name : 'All',
            "Executive" => $employeeId != null ? Employee::find($employeeId)?->emp_name : 'All',
        ];


        if($categoryId == null){
            // $reportData = DeliveryOrder::leftJoin('delivery_order_details', 'delivery_orders.id', '=', 'delivery_order_details.delivery_order_id')
            // ->leftJoin('products', 'delivery_order_details.product_id', '=', 'products.id')
            // ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            // ->leftJoin('customers', 'customers.id', '=', 'delivery_orders.customer_id')
            // ->leftJoin('sales_zones', 'sales_zones.id', '=', 'delivery_orders.zone_id')

            $reportData = SalesReturn::leftJoin('sales_return_details', 'sales_return_details.sales_return_id', '=', 'sales_returns.id')
                                    ->leftJoin('products', 'products.id', '=', 'sales_return_details.product_id')
                                    ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                                    ->leftJoin('delivery_challans', 'delivery_challans.id', '=', 'sales_returns.delivery_challan_id')
                                    ->leftJoin('delivery_orders', 'delivery_orders.id', 'delivery_challans.delivery_order_id')
                                    ->leftJoin('customers', 'customers.id', '=', 'delivery_orders.customer_id')
                                    ->leftJoin('sales_zones', 'sales_zones.id', '=', 'delivery_orders.zone_id')
                                    ->leftJoin('employees', 'employees.id', '=', 'delivery_orders.employee_id')

            ->select('sales_returns.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('sales_zones.id', $zoneId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customers.id', $customerId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('employees.id', $employeeId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('sales_returns.return_date', [$from_date, $to_date]);
            })
            // ->with(['deliveryOrderDetails' => function($query) use($categoryId, $productId){
            //     $query->when($productId, function($query2) use($productId){
            //         $query2->where('product_id', $productId);
            //     })->when($categoryId && !$productId, function($query2) use($categoryId){
            //         $query2->leftJoin('products', 'product_id', 'products.id')->where('products.category_id', $categoryId);
            //     });
            // }])
            ->with('sales_return_details', 'delivery_challan', 'sales_return_details.product', 'delivery_challan.deliveryOrder', 'delivery_challan.deliveryOrder.employee', 'delivery_challan.customer','delivery_challan.deliveryOrder.zone')
            ->groupBy('sales_returns.id')
            ->orderBy('sales_returns.id', 'desc')
            ->get();
        }
        else{
            $salesReturnDetails = SalesReturnDetail::rightJoin('sales_returns', 'sales_return_details.sales_return_id', '=', 'sales_returns.id')
            ->leftJoin('products', 'products.id', '=', 'sales_return_details.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('delivery_challans', 'delivery_challans.id', '=', 'sales_returns.delivery_challan_id')
            ->leftJoin('delivery_orders', 'delivery_orders.id', 'delivery_challans.delivery_order_id')
            ->leftJoin('customers', 'customers.id', '=', 'delivery_orders.customer_id')
            ->leftJoin('sales_zones', 'sales_zones.id', '=', 'delivery_orders.zone_id')
            ->leftJoin('employees', 'employees.id', '=', 'delivery_orders.employee_id')
            ->select('sales_return_details.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('sales_zones.id', $zoneId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customers.id', $customerId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('employees.id', $employeeId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('sales_returns.return_date', [$from_date, $to_date]);
            })
            // ->with(['deliveryOrderDetails' => function($query) use($categoryId, $productId){
            //     $query->when($productId, function($query2) use($productId){
            //         $query2->where('product_id', $productId);
            //     })->when($categoryId && !$productId, function($query2) use($categoryId){
            //         $query2->leftJoin('products', 'product_id', 'products.id')->where('products.category_id', $categoryId);
            //     });
            // }])
            // ->groupBy('delivery_order_details.id')
            ->with('sales_return', 'product', 'sales_return.delivery_challan', 'sales_return.delivery_challan.deliveryOrder', 'sales_return.delivery_challan.deliveryOrder.zone', 'sales_return.delivery_challan.deliveryOrder.employee', 'sales_return.delivery_challan.customer')
            ->orderBy('delivery_orders.id', 'desc')
            ->get();

            $reportData = $salesReturnDetails->mapToGroups(function ($item, $key) {
                return [$item->product_id => $item];
            });

        }




        $pdf = PDF::loadView(
            $categoryId == null && $productId == null ? 'sales::sales-return-report.print' : 'sales::sales-return-report.category_product_wise_print',
            compact('reportData', 'print_date_time', 'from_date', 'to_date', 'search_criteria'),
            [],
            [
                'title' => 'Sales Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('sales_return report.pdf');
    }

    public function deliveryChallan()
    {
        $formType = 'delivery-challan';
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $products = Product::orderBy('name')->pluck('name', 'id');
        $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');
        // $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::delivery-challan-report.index', compact('formType', 'categories', 'customers', 'products', 'zones', 'employees'));
    }

    public function deliveryChallanReport(Request $request)
    {

        $request->validate([
            'from_date' => 'required',
            'to_date' => ['required',  new FromToDateRule()]
        ]);



        // $pdf = PDF::loadView('sales::delivery-sales-collections-report.print',  [], [
        //     'title' => 'Certificate',
        //     'format' => 'A4-L',
        //     'orientation' => 'L'
        // ]);
        // $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        // $pdf->getMpdf()->showWatermarkText = true;
        // return $pdf->stream('Certificate.pdf');


        // $reportData = DeliveryOrder::with('deliveryOrderDetails', 'deliveryOrderDetails.product', 'deliveryOrderDetails.product.unit', 'customer')
        //     ->whereBetween('date', [$request->from_date, $request->to_date])
        //     ->get()->groupBy('date');

        //$deliveryOrders = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer')->orderBy('id', 'desc')->get();
        $productId = $request->product_id;
        $categoryId = $request->category_id;
        $zoneId = $request->zone_id;
        $customerId = $request->customer_id ;
        $employeeId = $request->employee_id;
        $from_date = $request->from_date ;
        $to_date = $request->to_date ;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Zone" => $zoneId != null ? SalesZone::find($zoneId)?->zone : 'All',
            "Category" => $categoryId != null ? Category::find($categoryId)?->name : 'All',
            "Product" => $productId != null ? Product::find($productId)?->name : 'All',
            "Customer" => $customerId != null ? Customer::find($customerId)?->name : 'All',
            "Executive" => $employeeId != null ? Employee::find($employeeId)?->emp_name : 'All',
        ];
// dd($categoryId);
        if($categoryId == null){
            $reportData = DeliveryChallan::leftJoin('delivery_challan_details', 'delivery_challan_details.delivery_challan_id', '=', 'delivery_challans.id')
            ->leftJoin('products', 'products.id', '=', 'delivery_challan_details.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('delivery_orders', 'delivery_orders.id', '=', 'delivery_challans.delivery_order_id')
            ->select('delivery_challans.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('delivery_orders.zone_id', $zoneId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('delivery_orders.employee_id', $employeeId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('delivery_challans.customer_id', $customerId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('delivery_challans.delivery_date', [$from_date, $to_date]);
            })
            // ->with(['deliveryOrderDetails' => function($query) use($categoryId, $productId){
            //     $query->when($productId, function($query2) use($productId){
            //         $query2->where('product_id', $productId);
            //     })->when($categoryId && !$productId, function($query2) use($categoryId){
            //         $query2->leftJoin('products', 'product_id', 'products.id')->where('products.category_id', $categoryId);
            //     });
            // }])
            ->with('deliveryChallanDetails', 'deliveryChallanDetails.product', 'deliveryOrder', 'deliveryOrder.employee', 'customer','deliveryOrder.zone')
            ->groupBy('delivery_challans.id')
            ->orderBy('delivery_challans.id', 'desc')
            ->get();
        }
        else{
            $deliveryChallanDetails = DeliveryChallanDetail::rightJoin('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.delivery_challan_id')
            ->leftJoin('products', 'products.id', '=', 'delivery_challan_details.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('delivery_orders', 'delivery_orders.id', '=', 'delivery_challans.delivery_order_id')
            ->select('delivery_challan_details.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('delivery_orders.zone_id', $zoneId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('delivery_orders.employee_id', $employeeId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('delivery_challans.customer_id', $customerId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('delivery_challans.delivery_date', [$from_date, $to_date]);
            })
            ->with('deliveryChallan', 'deliveryChallan.deliveryOrder', 'measuringUnit','product' , 'deliveryChallan.customer', 'deliveryChallan.deliveryOrder.employee', 'deliveryChallan.deliveryOrder.zone')
            ->orderBy('delivery_challans.id', 'desc')
            ->get();

            $reportData = $deliveryChallanDetails->mapToGroups(function ($item, $key) {
                return [$item->product_id => $item];
            });

        }




        $pdf = PDF::loadView(
            $categoryId == null && $productId == null ? 'sales::delivery-challan-report.print' : 'sales::delivery-challan-report.category_product_wise_print',
            compact('reportData', 'print_date_time', 'from_date', 'to_date', 'search_criteria'),
            [],
            [
                'title' => 'Sales Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('delivery_challan_report.pdf');
    }

    public function doWiseDeliveryChallan()
    {
        $formType = 'do-wise-delivery-challan';
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $customers = Customer::orderBy('name')->pluck('name', 'id');
        $products = Product::orderBy('name')->pluck('name', 'id');
        $zones = SalesZone::orderBy('zone')->pluck('zone', 'id');
        // $units = MeasuringUnit::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('emp_name')->pluck('emp_name', 'id');
        return view('sales::do-wise-delivery-challan-report.index', compact('formType', 'categories', 'customers', 'products', 'zones', 'employees'));
    }

    public function doWiseDeliveryChallanReport(Request $request)
    {
        // dd($request);
        $request->validate([
            'from_date' => 'required',
            'to_date' => ['required',  new FromToDateRule()]
        ]);


        // $pdf = PDF::loadView('sales::delivery-sales-collections-report.print',  [], [
        //     'title' => 'Certificate',
        //     'format' => 'A4-L',
        //     'orientation' => 'L'
        // ]);
        // $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        // $pdf->getMpdf()->showWatermarkText = true;
        // return $pdf->stream('Certificate.pdf');


        // $reportData = DeliveryOrder::with('deliveryOrderDetails', 'deliveryOrderDetails.product', 'deliveryOrderDetails.product.unit', 'customer')
        //     ->whereBetween('date', [$request->from_date, $request->to_date])
        //     ->get()->groupBy('date');

        //$deliveryOrders = DeliveryOrder::with('deliveryOrderDetails.product', 'employee', 'customer')->orderBy('id', 'desc')->get();
        $productId = $request->product_id;
        $categoryId = $request->category_id;
        $zoneId = $request->zone_id;
        $customerId = $request->customer_id ;
        $employeeId = $request->employee_id;
        $from_date = $request->from_date ;
        $to_date = $request->to_date ;
        $print_date_time = Carbon::now();
        $print_date_time->timezone('Asia/Dhaka');

        $search_criteria = [
            "Zone" => $zoneId != null ? SalesZone::find($zoneId)?->zone : 'All',
            "Category" => $categoryId != null ? Category::find($categoryId)?->name : 'All',
            "Product" => $productId != null ? Product::find($productId)?->name : 'All',
            "Customer" => $customerId != null ? Customer::find($customerId)?->name : 'All',
            "Executive" => $employeeId != null ? Employee::find($employeeId)?->emp_name : 'All',
        ];
// dd($categoryId);
        if($categoryId == null){
            $reportData = DeliveryOrder::leftJoin('delivery_order_details', 'delivery_orders.id', '=', 'delivery_order_details.delivery_order_id')
            ->leftJoin('products', 'delivery_order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('customers', 'customers.id', '=', 'delivery_orders.customer_id')
            ->leftJoin('sales_zones', 'sales_zones.id', '=', 'delivery_orders.zone_id')
            ->leftJoin('employees', 'employees.id', '=', 'delivery_orders.employee_id')
            ->select('delivery_orders.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('sales_zones.id', $zoneId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('employees.id', $employeeId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customers.id', $customerId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('delivery_orders.date', [$from_date, $to_date]);
            })
            // ->with(['deliveryOrderDetails' => function($query) use($categoryId, $productId){
            //     $query->when($productId, function($query2) use($productId){
            //         $query2->where('product_id', $productId);
            //     })->when($categoryId && !$productId, function($query2) use($categoryId){
            //         $query2->leftJoin('products', 'product_id', 'products.id')->where('products.category_id', $categoryId);
            //     });
            // }])
            ->with('deliveryOrderDetails', 'deliveryChallans', 'deliveryChallans.deliveryChallanDetails', 'employee', 'customer','zone')
            ->groupBy('delivery_orders.id')
            ->orderBy('delivery_orders.id', 'desc')
            ->get();
        }
        else{
            $deliveryOrderDetails = deliveryOrderDetail::rightJoin('delivery_orders', 'delivery_orders.id', '=', 'delivery_order_details.delivery_order_id')
            ->leftJoin('products', 'delivery_order_details.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('customers', 'customers.id', '=', 'delivery_orders.customer_id')
            ->leftJoin('sales_zones', 'sales_zones.id', '=', 'delivery_orders.zone_id')
            ->leftJoin('employees', 'employees.id', '=', 'delivery_orders.employee_id')
            ->select('delivery_order_details.*')
            ->when($productId, function ($query, $productId) {
                return $query->where('products.id', $productId);
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('categories.id', $categoryId);
            })
            ->when($zoneId, function ($query, $zoneId) {
                return $query->where('sales_zones.id', $zoneId);
            })
            ->when($employeeId, function ($query, $employeeId) {
                return $query->where('employees.id', $employeeId);
            })
            ->when($customerId, function ($query, $customerId) {
                return $query->where('customers.id', $customerId);
            })
            ->when($from_date && $to_date, function ($query) use($from_date, $to_date) {
                return $query->whereBetween('delivery_orders.date', [$from_date, $to_date]);
            })
            // ->with(['deliveryOrderDetails' => function($query) use($categoryId, $productId){
            //     $query->when($productId, function($query2) use($productId){
            //         $query2->where('product_id', $productId);
            //     })->when($categoryId && !$productId, function($query2) use($categoryId){
            //         $query2->leftJoin('products', 'product_id', 'products.id')->where('products.category_id', $categoryId);
            //     });
            // }])
            // ->groupBy('delivery_order_details.id')
            ->when($categoryId && !$productId, function ($query) use($categoryId) {
                $query->with(['deliveryOrder.deliveryChallans'=>function($query) use($categoryId){
                    $query->leftJoin('delivery_challan_details', 'delivery_challan_details.delivery_challan_id', '=', 'delivery_challans.id')
                            ->leftJoin('products', 'delivery_challan_details.product_id', '=', 'products.id')
                            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                            ->where('products.category_id', $categoryId)
                            ->groupBy('delivery_challans.id')
                            ->select('delivery_challans.*');
                }, 'deliveryOrder.deliveryChallans.deliveryChallanDetails'=>function($query) use($categoryId){
                    $query->leftJoin('products', 'delivery_challan_details.product_id', '=', 'products.id')
                            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                            ->where('products.category_id', $categoryId)
                            ->select('delivery_challan_details.*');
                } ]);
            })

            ->when($categoryId && $productId, function ($query) use($categoryId, $productId) {
                $query->with(['deliveryOrder.deliveryChallans'=>function($query) use($categoryId, $productId){
                    $query->leftJoin('delivery_challan_details', 'delivery_challan_details.delivery_challan_id', '=', 'delivery_challans.id')
                            ->leftJoin('products', 'delivery_challan_details.product_id', '=', 'products.id')
                            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                            ->where('products.category_id', $categoryId)
                            ->where('products.id', $productId)
                            ->groupBy('delivery_challans.id')
                            ->select('delivery_challans.*');
                }, 'deliveryOrder.deliveryChallans.deliveryChallanDetails'=>function($query) use($categoryId, $productId){
                    $query->leftJoin('products', 'delivery_challan_details.product_id', '=', 'products.id')
                            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                            ->where('products.category_id', $categoryId)
                            ->where('products.id', $productId)
                            ->select('delivery_challan_details.*');
                } ]);
            })
            ->with('deliveryOrder', 'product' , 'deliveryOrder.customer', 'deliveryOrder.employee', 'deliveryOrder.zone')
            ->orderBy('delivery_orders.id', 'desc')
            ->get();

            $reportData = $deliveryOrderDetails->mapToGroups(function ($item, $key) {
                return [$item->product_id => $item];
            });
            // dd($reportData);

        }

        $pdf = PDF::loadView(
            $categoryId == null && $productId == null ? 'sales::do-wise-delivery-challan-report.print' : 'sales::do-wise-delivery-challan-report.category_product_wise_print',
            compact('reportData', 'print_date_time', 'from_date', 'to_date', 'search_criteria'),
            [],
            [
                'title' => 'Sales Report',
                'format' => 'A4-L',
                'orientation' => 'L'
            ]
        );
        $pdf->getMpdf()->SetWatermarkText('Golden Ispat Limited');
        $pdf->getMpdf()->showWatermarkText = true;
        return $pdf->stream('DO_Wise_Delivery_Challan_Report.pdf');
    }

    public function getCategoryWiseProducts(Request $request){
        $products = Product::where('category_id', $request->category_id)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'text' => $product->name,
            ];
        })->toArray();
        return response()->json($products);
    }

}
