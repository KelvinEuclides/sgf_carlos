<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function itemCategoryindex()
    {
        if(\Auth::user()->can('manage category'))
        {
            $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'item')->get();

            return view('category.itemIndex', compact('categories'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function itemCategorycreate()
    {
        return view('category.itemCreate');
    }

    public function itemCategorystore(Request $request)
    {
        if(\Auth::user()->can('create category'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new Category();
            $category->name       = $request->name;
            $category->type       = 'item';
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            return redirect()->route('category.item.index')->with('success', __('Item category successfully created.'));

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function itemCategoryedit($id)
    {
        $category = Category::find($id);

        return view('category.itemEdit', compact('category'));
    }

    public function itemCategoryupdate(Request $request, $id)
    {
        $category = Category::find($id);
        if(\Auth::user()->can('edit category'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $category->name       = $request->name;
            $category->save();

            return redirect()->route('category.item.index')->with('success', __('Item category successfully updated.'));

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function itemCategorydestroy($id)
    {
        if(\Auth::user()->can('delete category'))
        {
            $category = Category::find($id);
            if($category)
            {
                $category->delete();

                return redirect()->route('category.item.index')->with('success', __('Item category successfully deleted .'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

//    ----------------------------------------Income Category---------------------------------------------------

    public function incomeCategoryindex()
    {
        if(\Auth::user()->can('manage category'))
        {
            $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'income')->get();

            return view('category.incomeIndex', compact('categories'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function incomeCategorycreate()
    {

        return view('category.incomeCreate');
    }


    public function incomeCategorystore(Request $request)
    {

        if(\Auth::user()->can('create category'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'color' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new Category();
            $category->name       = $request->name;
            $category->color       = $request->color;
            $category->type       = 'income';
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            return redirect()->route('category.income.index')->with('success', __('Income category successfully created.'));

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function incomeCategoryedit($id)
    {
        $category = Category::find($id);

        return view('category.incomeEdit', compact('category'));
    }


    public function incomeCategoryupdate(Request $request, $id)
    {
        $category = Category::find($id);
        if(\Auth::user()->can('edit category'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'color' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $category->name       = $request->name;
            $category->color       = $request->color;
            $category->save();

            return redirect()->route('category.income.index')->with('success', __('Income category successfully updated.'));

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function incomeCategorydestroy($id)
    {
        if(\Auth::user()->can('delete category'))
        {
            $category = Category::find($id);
            if($category)
            {
                $category->delete();

                return redirect()->route('category.income.index')->with('success', __('Income category successfully deleted .'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    //    ----------------------------------------Expense Category---------------------------------------------------

    public function expenseCategoryindex()
    {
        if(\Auth::user()->can('manage category'))
        {
            $categories = Category::where('created_by', '=', \Auth::user()->creatorId())->where('type', 'expense')->get();

            return view('category.expenseIndex', compact('categories'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function expenseCategorycreate()
    {

        return view('category.expenseCreate');
    }


    public function expenseCategorystore(Request $request)
    {

        if(\Auth::user()->can('create category'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'color' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new Category();
            $category->name       = $request->name;
            $category->color       = $request->color;
            $category->type       = 'expense';
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            return redirect()->route('category.expense.index')->with('success', __('Expense category successfully created.'));

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function expenseCategoryedit($id)
    {
        $category = Category::find($id);

        return view('category.expenseEdit', compact('category'));
    }


    public function expenseCategoryupdate(Request $request, $id)
    {
        $category = Category::find($id);
        if(\Auth::user()->can('edit category'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'color' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $category->name       = $request->name;
            $category->color       = $request->color;
            $category->save();

            return redirect()->route('category.expense.index')->with('success', __('Expense category successfully updated.'));

        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function expenseCategorydestroy($id)
    {
        if(\Auth::user()->can('delete category'))
        {
            $category = Category::find($id);
            if($category)
            {
                $category->delete();

                return redirect()->route('category.expense.index')->with('success', __('Expense category successfully deleted .'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

}
