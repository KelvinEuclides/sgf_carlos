@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_small_logo=Utility::getValByName('company_small_logo');
@endphp
<div class="sidenav custom-sidenav" id="sidenav-main">
    <!-- Sidenav header -->
    <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" class="navbar-brand-img"/>
        </a>
        <div class="ml-auto">
            <!-- Sidenav toggler -->
            <div class="sidenav-toggler sidenav-toggler-dark d-md-none" data-action="sidenav-unpin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="scrollbar-inner">
        <div class="div-mega">
            <ul class="navbar-nav navbar-nav-docs">
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link {{ (Request::route()->getName() == 'home') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>{{__('Dashboard')}}
                    </a>
                </li>
                @if(\Auth::user()->type=='customer')
                    <li class="nav-item">
                        <a href="{{route('customer.estimation')}}" class="nav-link {{ (Request::route()->getName() == 'customer.estimation' || Request::route()->getName() == 'customer.estimation.show') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i>{{__('Estimations')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('customer.invoice')}}" class="nav-link {{ (Request::route()->getName() == 'customer.invoice' || Request::route()->getName() == 'customer.invoice.show') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i>{{__('Invoices')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('customer.payment')}}" class="nav-link {{ (Request::route()->getName() == 'customer.payment') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-alt"></i>{{__('Transactions')}}
                        </a>
                    </li>
                @endif
                @if(\Auth::user()->type=='vendor')
                    <li class="nav-item">
                        <a href="{{route('vendor.bill')}}" class="nav-link {{ (Request::route()->getName() == 'vendor.bill' || Request::route()->getName() == 'vendor.bill.show') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i>{{__('Bills')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('vendor.payment')}}" class="nav-link {{ (Request::route()->getName() == 'vendor.payment') ? 'active' : '' }}">
                            <i class="fas fa-money-bill-alt"></i>{{__('Transactions')}}
                        </a>
                    </li>
                @endif
                @if(\Auth::user()->type=='super admin')
                    <li class="nav-item">
                        <a href="{{route('users.index')}}" class="nav-link {{ (Request::route()->getName() == 'users.index') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>{{__('Company')}}
                        </a>
                    </li>
                @endif
                @if( Gate::check('manage user') || Gate::check('manage customer') || Gate::check('manage vendor'))
                    <li class="nav-item">
                        <a class="nav-link collapsed {{ (Request::route()->getName() == 'users.index' || Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show' || Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'active' : '' }}" href="#navbar-members" data-toggle="collapse" role="button"
                           aria-expanded="{{ ( Request::route()->getName() == 'users.index' || Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show' || Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'true' : 'false' }}" aria-controls="navbar-members">
                            <i class="fas fa-users"></i>{{__('Members')}}
                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse {{ ( Request::route()->getName() == 'users.index' || Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show' || Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'show' : '' }}" id="navbar-members">
                            <ul class="nav flex-column">
                                @can('manage user')
                                    <li class="nav-item">
                                        <a href="{{route('users.index')}}" class="nav-link {{ (Request::route()->getName() == 'users.index') ? 'a-active' : '' }}">{{__('Users')}}</a>
                                    </li>
                                @endcan
                                @can('manage customer')
                                    <li class="nav-item">
                                        <a href="{{route('customers.index')}}" class="nav-link {{ (Request::route()->getName() == 'customers.index' || Request::route()->getName() == 'customers.show') ? 'a-active' : '' }}">{{__('Customers')}}</a>
                                    </li>
                                @endcan
                                @can('manage vendor')
                                    <li class="nav-item">
                                        <a href="{{route('vendors.index')}}" class="nav-link {{ (Request::route()->getName() == 'vendors.index' || Request::route()->getName() == 'vendors.show') ? 'a-active' : '' }}">{{__('Vendors')}}</a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                @endif

                @can('manage item')
                    <li class="nav-item">
                        <a href="{{route('item.index')}}" class="nav-link {{ (Request::route()->getName() == 'item.index') ? 'active' : '' }}">
                            <i class="fas fa-sitemap"></i>{{__('Items')}}
                        </a>
                    </li>
                @endcan


                @if( Gate::check('manage banking'))
                    <li class="nav-item">
                        <a class="nav-link collapsed {{ (Request::route()->getName() == 'account.index' || Request::route()->getName() == 'transfer.index') ? 'active' : '' }}" href="#navbar-banking" data-toggle="collapse" role="button" aria-expanded="{{ (Request::route()->getName() == 'account.index' || Request::route()->getName() == 'transfer.index') ? 'true' : 'false' }}" aria-controls="navbar-banking">
                            <i class="fas fa-money-check"></i>{{__('Banking')}}
                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse {{ (Request::route()->getName() == 'account.index' || Request::route()->getName() == 'transfer.index') ? 'show' : '' }}" id="navbar-banking">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{route('account.index')}}" class="nav-link {{ (Request::route()->getName() == 'account.index') ? 'a-active' : '' }}">{{__('Accounts')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('transfer.index')}}" class="nav-link {{ (Request::route()->getName() == 'transfer.index') ? 'a-active' : '' }}">{{__('Transfers')}}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if( Gate::check('manage invoice') || Gate::check('manage income') || Gate::check('manage estimation'))
                    <li class="nav-item">
                        <a class="nav-link collapsed {{ (Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show' || Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create' || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show'
                || Request::route()->getName() =='income.index') ?'active' : '' }}" href="#navbar-sales" data-toggle="collapse" role="button" aria-expanded="{{ (Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create'
                         || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show' || Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create'
                         || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show' || Request::route()->getName() == 'income.index') ? 'true' : 'false' }}"
                           aria-controls="navbar-sales">
                            <i class="fas fa-file-invoice"></i>{{__('Sales')}}
                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse {{ (Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show'  ||Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create' || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show'
            || Request::route()->getName() == 'income.index') ? 'show'
                         : '' }}" id="navbar-sales">
                            <ul class="nav flex-column">
                                @can('manage estimation')
                                    <li class="nav-item">
                                        <a href="{{route('estimation.index')}}" class="nav-link {{ (Request::route()->getName() == 'estimation.index' || Request::route()->getName() == 'estimation.create' || Request::route()->getName() == 'estimation.edit' || Request::route()->getName() == 'estimation.show') ? 'a-active' : '' }}">
                                            {{__('Estimations')}}
                                        </a>
                                    </li>
                                @endcan
                                @can('manage invoice')
                                    <li class="nav-item">
                                        <a href="{{route('invoice.index')}}" class="nav-link {{ (Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show') ? 'a-active' : '' }}">{{__('Invoices')}}</a>
                                    </li>
                                @endcan
                                @can('manage income')
                                    <li class="nav-item">
                                        <a href="{{route('income.index')}}" class="nav-link {{ (Request::route()->getName() == 'income.index') ? 'a-active' : '' }}">{{__('Incomes')}}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endif
                @if( Gate::check('manage bill') ||  Gate::check('manage expense'))
                    <li class="nav-item">
                        <a class="nav-link collapsed {{ (Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show'  || Request::route()->getName() == 'expense.index') ? 'active' : '' }}" href="#navbar-purchses" data-toggle="collapse" role="button" aria-expanded="{{ (Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' ||
                        Request::route()
                        ->getName() == 'bill.edit' ||
                         Request::route()->getName() == 'bill.show') ? 'true' : 'false' }}" aria-controls="navbar-purchses">
                            <i class="fas fa-receipt"></i>{{__('Purchase')}}
                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse {{ (Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show'  || Request::route()->getName() == 'expense.index') ? 'show' : '' }}" id="navbar-purchses">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{route('bill.index')}}" class="nav-link {{ (Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show') ? 'a-active' : '' }}">{{__('Bills')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('expense.index')}}" class="nav-link {{ (Request::route()->getName() == 'expense.index') ? 'a-active' : '' }}">{{__('Expenses')}}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if( Gate::check('manage subscription'))
                    <li class="nav-item">
                        <a href="{{route('subscription.index')}}" class="nav-link {{ (Request::route()->getName() == 'subscription.index' || Request::route()->getName() == 'stripe') ? 'active' : '' }}">
                            <i class="fas fa-swatchbook"></i>{{__('Subscription')}}
                        </a>
                    </li>
                @endif
                @if(\Auth::user()->type=='super admin')
                    <li class="nav-item">
                        <a href="{{route('voucher.index')}}" class="nav-link {{ (Request::route()->getName() == 'voucher.index' || Request::route()->getName() == 'voucher.show') ? 'active' : '' }}">
                            <i class="fas fa-gift"></i>{{__('Voucher')}}
                        </a>
                    </li>
                @endif
                @if(\Auth::user()->type=='super admin' || \Auth::user()->type=='company')
                    <li class="nav-item">
                        <a href="{{route('subscriber.index')}}" class="nav-link {{ (Request::route()->getName() == 'subscriber.index') ? 'active' : '' }}">
                            <i class="fas fa-cart-plus"></i>{{__('Subscriber')}}
                        </a>
                    </li>
                @endif
                @if( Gate::check('manage summary'))
                    <li class="nav-item">
                        <a class="nav-link {{ (Request::segment(1) == 'summary' )?' active':'collapsed'}}" href="#navbar-summary" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'summary')?'true':'false'}}" aria-controls="navbar-reports">
                            <i class="fas fa-chart-line"></i>{{__('Summary')}}
                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse {{ (Request::segment(1) == 'summary')?'show':''}}" id="navbar-summary">
                            <ul class="nav flex-column submenu-ul">
                                <li class="nav-item ">
                                    <a href="{{route('estimation.summary')}}" class="nav-link {{ (Request::route()->getName() == 'estimation.summary' || Request::route()->getName() == 'estimation.summary.list' ) ? 'a-active' : '' }}">{{ __('Estimation') }}</a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{route('invoice.summary')}}" class="nav-link {{ (Request::route()->getName() == 'invoice.summary' || Request::route()->getName() == 'invoice.summary.list' ) ? 'a-active' : '' }}">{{ __('Invoice') }}</a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{route('bill.summary')}}" class="nav-link {{ (Request::route()->getName() == 'bill.summary' || Request::route()->getName() == 'bill.summary.list' ) ? 'a-active' : '' }}">{{ __('Bill') }}</a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{route('sales.summary')}}" class="nav-link {{ (Request::route()->getName() == 'sales.summary' || Request::route()->getName() == 'sales.summary.list' ) ? 'a-active' : '' }}">{{ __('Sales') }}</a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{route('purchase.summary')}}" class="nav-link {{ (Request::route()->getName() == 'purchase.summary' || Request::route()->getName() == 'purchase.summary.list' ) ? 'a-active' : '' }}">{{ __('Purchase') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if( Gate::check('manage category') ||  Gate::check('manage tax') ||  Gate::check('manage unit'))
                    <li class="nav-item">
                        <a class="nav-link collapsed {{ (Request::route()->getName() == 'category.item.index' || Request::route()->getName() == 'category.income.index' || Request::route()->getName() == 'category.expense.index' || Request::route()->getName() == 'tax.index' || Request::route()->getName() == 'unit.index') ? 'active' : '' }}" href="#navbar-getting-started" data-toggle="collapse" role="button" aria-expanded="{{ (Request::route()->getName() == 'category.item.index' || Request::route()
                    ->getName() == 'category.income.index' ||
                    Request::route()->getName() == 'category.expense
                    .index' || Request::route()->getName() == 'tax.index' || Request::route()->getName() == 'unit.index') ? 'true' : 'false' }}" aria-controls="navbar-getting-started">
                            <i class="fab fa-meetup"></i>{{__('Setup')}}
                            <i class="fas fa-sort-up"></i>
                        </a>
                        <div class="collapse {{ (Request::route()->getName() == 'category.item.index' || Request::route()->getName() == 'category.income.index' || Request::route()->getName() == 'category.expense.index' || Request::route()->getName() == 'tax.index' || Request::route()->getName() == 'unit.index') ? 'show' : '' }}" id="navbar-getting-started">
                            <ul class="nav flex-column">
                                @can('manage category')
                                    <li class="nav-item">
                                        <a href="{{route('category.item.index')}}" class="nav-link  {{ (Request::route()->getName() == 'category.item.index') ? 'a-active' : '' }}">{{__('Item Category')}}</a>
                                    </li>
                                @endcan
                                @can('manage category')
                                    <li class="nav-item">
                                        <a href="{{route('category.income.index')}}" class="nav-link {{ (Request::route()->getName() == 'category.income.index') ? 'a-active' : '' }}">{{__('Income Category')}}</a>
                                    </li>
                                @endcan
                                @can('manage category')
                                    <li class="nav-item">
                                        <a href="{{route('category.expense.index')}}" class="nav-link {{ (Request::route()->getName() == 'category.expense.index') ? 'a-active' : '' }}">{{__('Expense Category')}}</a>
                                    </li>
                                @endcan
                                @can('manage tax')
                                    <li class="nav-item">
                                        <a href="{{route('tax.index')}}" class="nav-link {{ (Request::route()->getName() == 'tax.index') ? 'a-active' : '' }}">{{__('Taxes')}}</a>
                                    </li>
                                @endcan
                                @can('manage unit')
                                    <li class="nav-item">
                                        <a href="{{route('unit.index')}}" class="nav-link {{ (Request::route()->getName() == 'unit.index') ? 'a-active' : '' }}">{{__('Units')}}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endif
                @if(\Auth::user()->type=='company' || \Auth::user()->type=='super admin')
                    <li class="nav-item">
                        <a href="{{route('setting.index')}}" class="nav-link {{ (Request::route()->getName() == 'setting.index') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>{{__('Settings')}}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
