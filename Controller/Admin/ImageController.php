<?php

namespace Plugin\CMBlog\Controller\Admin;

use Eccube\Controller\AbstractController;
use Plugin\CMBlog\Repository\ConfigRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class ImageController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * BlogController constructor.
     *
     * @param ConfigRepository $blogRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }
    /**
     * @Route("/%eccube_admin_route%/cm_blog/upload", name="cm_blog_admin_upload")
     */
    public function upload(Request $request)
    {
        try {
            $config = $this->configRepository->get();

            // File Route.
            $userDataPath = 'html/user_data/';
            $fileRoute = $userDataPath.$config->getImagePath().'/';

            $fieldname = "upload";

            // Get filename.
            $filename = explode(".", $_FILES[$fieldname]["name"]);

            // Validate uploaded files.
            // Do not use $_FILES["file"]["type"] as it can be easily forged.
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            // Get temp file name.
            $tmpName = $_FILES[$fieldname]["tmp_name"];

            // Get mime type.
            $mimeType = finfo_file($finfo, $tmpName);

            // Get extension. You must include fileinfo PHP extension.
            $extension = end($filename);

            // Allowed extensions.
            // $allowedExts = array("gif", "jpeg", "jpg", "png", "svg", "blob");
            $allowedExts = array("jpeg", "jpg", "png");

            // Allowed mime types.
            // $allowedMimeTypes = array("image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png", "image/svg+xml");
            $allowedMimeTypes = array("image/jpeg", "image/pjpeg", "image/png");

            // Validate image.
            if (!in_array(strtolower($mimeType), $allowedMimeTypes) || !in_array(strtolower($extension), $allowedExts)) {
                throw new \Exception("File does not meet the validation.");
            }

            // Generate new random name.
            $name = sha1(microtime()) . "." . $extension;
            $fullNamePath = $fileRoute . $name;

            // Check server protocol and load resources accordingly.
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
                $protocol = "https://";
            } else {
                $protocol = "http://";
            }

            // フォルダー確認
            $filesystem = new Filesystem();
            if (!$filesystem->exists($fileRoute))
                $filesystem->mkdir($fileRoute);

            // Save file in the uploads folder.
            move_uploaded_file($tmpName, $fullNamePath);

            return new JsonResponse([
                'uploaded' => 1,
                'filename' => $name,
                'url' => $protocol.$_SERVER["HTTP_HOST"].'/'.$fullNamePath,
            ]);

        } catch (\Exception $exception) {

            return new JsonResponse([
                'uploaded' => 0,
                'error' => array(
                    'message' => $exception->getMessage(),
                )
            ]);
        }
    }

}
