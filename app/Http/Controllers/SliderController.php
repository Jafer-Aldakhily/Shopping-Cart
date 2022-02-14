<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;


class SliderController extends Controller
{
    public function addSlider()
    {
        return view('admin.sliders.addslider');
    }

    public function sliders()
    {
        $sliders = Slider::all();
        return view('admin.sliders.sliders')->with('sliders' , $sliders);
    }


    public function saveSlider(Request $request)
    {
        $request->validate([
            'description1' => 'required',
            'description2' => 'required',
            'slider_image' => 'image|required|max:1999',
        ]);

        if($request->hasFile('slider_image'))
        {
        // 1: get file name with extension
        $fileNameWithExe = $request->file('slider_image')->getClientOriginalName();
        // 2: get file  extension
        $fileExe = $request->file('slider_image')->getClientOriginalExtension();
        // 3: get file name without extension
        $fileNameWithoutExe = pathinfo($fileNameWithExe,PATHINFO_FILENAME);
        // 4: file to store
        $fileToStore = $fileNameWithoutExe.'_'.time().'.'.$fileExe;
        // 5: upload file
        $path = $request->file('slider_image')->storeAs('public/slider_images/' , $fileToStore);
        }else{
            $fileToStore = 'noimage.png';
        }

        $slider = new Slider();
        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');
        $slider->slider_image = $fileToStore;
        $slider->status = 1;
        $slider->save();
        return redirect()->back()->with('success' , 'The slider has been added successfully !!');
    }



    public function editSlider($id)
    {
        $slider = Slider::find($id);
        return view('admin.sliders.editslider')->with('slider' , $slider);
    }



    public function updateSlider(Request $request)
    {
        $request->validate([
            'description1' => 'required',
            'description2' => 'required',
            'slider_image' => 'image|required|max:1999',
        ]);

        $slider = Slider::find($request->input('id'));

        if($request->hasFile('slider_image'))
        {
        // 1: get file name with extension
        $fileNameWithExe = $request->file('slider_image')->getClientOriginalName();
        // 2: get file  extension
        $fileExe = $request->file('slider_image')->getClientOriginalExtension();
        // 3: get file name without extension
        $fileNameWithoutExe = pathinfo($fileNameWithExe,PATHINFO_FILENAME);
        // 4: file to store
        $fileToStore = $fileNameWithoutExe.'_'.time().'.'.$fileExe;
        // 5: upload file
        $path = $request->file('slider_image')->storeAs('public/slider_images/' , $fileToStore);
        }
        if($slider->slider_image != 'noimage.png')
        {
            Storage::delete('public/slider_images/' . $slider->slider_image);
        }



        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');
        $slider->slider_image = $fileToStore;
        $slider->update();
        return redirect()->back()->with('success' , 'The slider has been updated successfully !!');
    }


    public function deleteSlider($id)
    {
        $slider = Slider::find($id);
        if($slider->slider_image != 'noimage.png')
        {
            Storage::delete('/public/slider_images/' . $slider->slider_image);

        }
        $slider->delete();
        return redirect()->back()->with('success' , 'The slider has been deleted successfully !!');
    }


    public function active_slider($id)
    {
        $slider = Slider::find($id);
        $slider->status = 1;
        $slider->update();
        return \redirect()->back()->with('success' , 'The slider activted successfully !!');
    }

    public function unactive_slider($id)
    {
        $slider = Slider::find($id);
        $slider->status = 0;
        $slider->update();
        return \redirect()->back()->with('success' , 'The slider unactivted successfully !!');
    }



}
