//Developer: wmfrancia
//github: wmfrancia
//stackoverflow: wmfrancia
//Free to Use, Attribution Appreciated
//2015


//This initiates the class and runs all the intelligence to scale and/or crop
$newImage = new Scropper;

//this will output the image and allow you to save it through your web browser
header("Content-Type: image/jpg");
echo $newImage->newImage;
    
class Scropper {
    
    protected $image;
    protected $imageHeight;
    protected $imageWidth;
    protected $newWidth;
    protected $newHeight;
    protected $shiftWidth;
    protected $shiftHeight;
    protected $fit;
    protected $imgName;
    public $newImage;
    
    function __construct() {
        
        //get path using url variable
        $this->imgName = ($_GET['img'] != null) ? $_GET['img'] : null;
        
        $path = "path/to/your/images".$this->imgName;
                
        
        //store imagick object into class variable
        $this->image = new Imagick($path);       
        
        //get original image dimensions
        $iSize = $this->image->getimagegeometry();
        
        //store image dimensions in class variables
        $this->imageHeight = $iSize['height'];
        $this->imageWidth = $iSize['width'];
        
        
        //store url variables
        $this->newWidth = ($_GET['w'] != null) ? $_GET['w'] : null;
        $this->newHeight = ($_GET['h'] != null) ? $_GET['h'] : null;
        $this->fit = ($_GET['fit'] != null) ? $_GET['fit'] : null;
        $this->shiftWidth = ($this->newWidth < $this->newHeight ) ? $this->newWidth*1.1 : 1;
        $this->shiftHeight = ($this->newHeight < $this->newWidth ) ? $this->newHeight/2 : 1;
        
        //decide what scale methos to use based on size
        $this->newImage = $this->scaleType();
       
        
        
    }
    private function scaleType() {
        
        //This is called when the fir parameter matches nocrop
        if(isset($this->fit) && $this->fit == "nocrop") {
    
            $this->image->scaleImage($this->newWidth,$this->newHeight,true);
            
            return $this->image->getImageBlob();
            
        }
        else {
            //Scaling for Large Images
            if($this->imageWidth >= 800) {

                $imagick = $this->scaleIt($this->image,800);

            }
            //Scaling for Medium Images
            elseif($this->imageWidth >= 500) {

                $imagick = $this->scaleIt($this->image,500);        

            } 
            //Scaling for Small image
            else {

                $imagick = $this->scaleIt($this->image,200);        

            }

            return $imagick;
        }
    }
    private function scaleIt($img,$width) {
        
        //scale image proportional
        $img->scaleImage($width,0);
        
        //get new sizes of scaled image
        $currentSize = $img->getimagegeometry();
        $currentWidth = $currentSize['width'];
        $currentHeight = $currentSize['height'];
        
        //set the starting position for the crop 
        $x = round($currentWidth/3);
        $y = round($currentHeight/11);
        
        //crop the image using the width and height from our url paremeters
        $img->cropImage($this->newWidth,$this->newHeight,$x,$y);        
        
        //return completed image
        return $img->getImageBlob();
                
    }

}
