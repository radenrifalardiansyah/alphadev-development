<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Frontend extends AN_Controller
{

    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Home Page
     */
    public function index()
    {
        $data['title']          = 'Home';
        $data['main_content']   = 'pages/home';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * About Us Page
     */
    public function about()
    {
        $data['title']          = 'About Us';
        $data['main_content']   = 'pages/about';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * Contact Us Page
     */
    public function contact()
    {
        $data['title']          = 'Contact Us';
        $data['main_content']   = 'pages/contact';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * team Page
     */
    public function team()
    {
        $data['title']          = 'Team';
        $data['main_content']   = 'pages/team';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * Product Page
     */
    public function product()
    {
        $data['title']          = 'Product';
        $data['main_content']   = 'pages/product';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * Testimoni Page
     */
    public function testimoni()
    {
        $data['title']          = 'Testimoni';
        $data['main_content']   = 'pages/testimoni';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * Event Page
     */
    public function event()
    {
        $data['title']          = 'Event';
        $data['main_content']   = 'pages/event';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * Tracking Page
     */
    public function tracking()
    {
        $data['title']          = 'Tracking';
        $data['main_content']   = 'pages/tracking';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * alphaking Page
     */
    public function alphaking()
    {
        $data['title']          = 'AlphaKing';
        $data['main_content']   = 'pages/alphaking';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * product alphaking Page
     */
    public function produk_alphaking()
    {
        $data['title']          = 'AlphaKing';
        $data['main_content']   = 'pages/produk/alphaking';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * peluang Page
     */
    public function peluang()
    {
        $data['title']          = 'Peluang';
        $data['main_content']   = 'pages/peluang';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }

    /**
     * tool promosi Page
     */
    public function tool_promosi()
    {
        $data['title']          = 'Tool Promosi';
        $data['main_content']   = 'pages/tool_promosi';
        $this->load->view(VIEW_FRONT . 'template', $data);
    }
}
