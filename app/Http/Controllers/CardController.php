<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Profile;

class CardController extends Controller
{
    function addCard(Request $request){
        // echo "<pre>";
        // print_r($request->all());
        // die;
        
            // if($request->hasFile('image'))
            // {
            //     $image = $request->file('image');
            //     $image_path = time().$image->getClientOriginalName();
            //     $image->move(public_path().'/card_images/', $image_path);
            // }
        if($request->image != null)
        {
            $image = $request->image;
            $extension = explode('/', explode(":", substr($image, 0, strpos($image, ";")))[1])[1];
            $replace = substr($image, 0, strpos($image, ',') + 1);
            $image = str_replace($replace, "", $image);
            $image = str_replace('', '+', $image);
            $image_path= time() . '.' . $extension;
            $image_decode = base64_decode($image);
            file_put_contents(public_path().'/card_images/'.$image_path, $image_decode);
        }
        $card = new Card();
        $card->user_id= auth()->user()->id;
        $card->name= $request->name;
        $card->email = $request->email;
        $card->phone = $request->phone;
        $card->company = $request->company;
        $card->designation = $request->designation;
        $card->address = $request->address;
        $card->country = $request->country;
        $card->city = $request->city;
        $card->linkedin = $request->linkiden;
        $card->website = $request->website;
        $card->image_path = $image_path;
        $card->save();
        return response()->json(['success'=>'Card Added Successfully']);
    }

    function getcard($card_id,$type){

        $card = Card::where('id', $card_id)->first();
        $type=$type;
        $profile=Profile::where('card_id',$card_id)->first();
        $data =compact('card','type','profile');
        return view('card_view')->with($data);
    }

    function delete_card($id){
        $card = Card::where('id',$id)->delete();
        return redirect('/home');
    }

    function update_card(Request $request){
        // echo "<pre>";
        // print_r($request->all());
        // die;
        $card_id=$request->card_id;
        $card = Card::where('id',$card_id)->first();
        $image_path=$card->image_path;
        if($request->image != null)
        {
            $image = $request->image;
            $extension = explode('/', explode(":", substr($image, 0, strpos($image, ";")))[1])[1];
            $replace = substr($image, 0, strpos($image, ',') + 1);
            $image = str_replace($replace, "", $image);
            $image = str_replace('', '+', $image);
            $image_path= time() . '.' . $extension;
            $image_decode = base64_decode($image);
            file_put_contents(public_path().'/card_images/'.$image_path, $image_decode);
        }
        $card->user_id= auth()->user()->id;
        $card->name= $request->name;
        $card->email = $request->email;
        $card->phone = $request->phone;
        $card->company = $request->company;
        $card->designation = $request->designation;
        $card->address = $request->address;
        $card->country = $request->country;
        $card->city = $request->city;
        $card->linkedin = $request->linkiden;
        $card->website = $request->website;
        $card->image_path = $image_path;
        $card->save();

        return response()->json(['success'=>'Card Updated Successfully']);
    }
    public function customize_card_index($card_id,$type){
        $card = Card::where('id', $card_id)->first();
        $type=$type;
        $data =compact('card','type');
        return view('customize_card')->with($data);
    }
}
