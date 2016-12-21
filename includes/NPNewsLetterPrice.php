<?php 

namespace App; 

class NPNewsLetterPrice
{

    protected static $fieldAttributes = [];

    /**
     * Set field attributes, group title, value and field title
     */
    public static function setFieldAttributes() {
        self::$fieldAttributes = [
            [
                'title' => 'Ready Made Content',
                'fields'=>[
                    [
                        'title'=> 'Ready Made Request 1',
                        'fieldName' => 'np_ready_made_content_request_1',
                        'fieldValue' => get_option( 'np_ready_made_content_request_1'),
                    ],
                    [
                        'title'=> 'Ready Made Request 2',
                        'fieldName' => 'np_ready_made_content_request_2',
                        'fieldValue' => get_option( 'np_ready_made_content_request_2'),
                    ],
                    [
                        'title'=> 'Ready Made Request 3',
                        'fieldName' => 'np_ready_made_content_request_3',
                        'fieldValue' => get_option( 'np_ready_made_content_request_3'),
                    ]
                ]
            ],
            [
                'title' => 'Delivery Class',
                'fields'=>[
                    [
                        'title'=> 'A4 First Class',
                        'fieldName' => 'np_a4_first_class',
                        'fieldValue' => get_option( 'np_a4_first_class'),
                    ],
                    [
                        'title'=> 'A4 Second Class',
                        'fieldName' => 'np_a4_second_class',
                        'fieldValue' => get_option( 'np_a4_second_class'),
                    ],
                    [
                        'title'=> 'A5 First Class',
                        'fieldName' => 'np_a5_first_class',
                        'fieldValue' => get_option( 'np_a5_first_class'),
                    ],
                    [
                        'title'=> 'A5 Second Class',
                        'fieldName' => 'np_a5_second_class',
                        'fieldValue' => get_option( 'np_a5_second_class'),
                    ],

                ]
            ],
            [
                'title' => 'Delivery Type',
                'fields'=>[
                    [
                        'title'=> 'A5 Self Mailer',
                        'fieldName' => 'np_a5_self_mailer',
                        'fieldValue' => get_option( 'np_a5_self_mailer'),
                    ],
                    [
                        'title'=> 'A4 Transparent Wallet',
                        'fieldName' => 'np_a4_transparent_wallet',
                        'fieldValue' => get_option( 'np_a4_transparent_wallet'),
                    ],
                    [
                        'title'=> 'A5 Transparent Wallet',
                        'fieldName' => 'np_a5_transparent_wallet',
                        'fieldValue' => get_option( 'np_a5_transparent_wallet'),
                    ]
                ]
            ],
            [
                'title' => 'Advert Cost Size',
                'fields'=>[
                    [
                        'title'=> 'Quarter Page',
                        'fieldName' => 'np_quarter_page',
                        'fieldValue' => get_option( 'np_quarter_page'),
                    ],
                    [
                        'title'=> 'A4 Halp Page',
                        'fieldName' => 'np_half_page',
                        'fieldValue' => get_option( 'np_half_page'),
                    ],
                    [
                        'title'=> 'A5 Full Page',
                        'fieldName' => 'np_full_page',
                        'fieldValue' => get_option( 'np_full_page'),
                    ]
                ]
            ]
        ];
    }

    /**
     * get field attributes after setFieldAttributes processed
     * @return array
     */
    public static function getFieldAttributes()
    {
        return self::$fieldAttributes;
    }

    /**
     * print html fields and update button
     * @param array $data
     */
    public static function printFieldHtml($data=[]) {
        if(count($data) == 0 ) {
            $data = self::getFieldAttributes();
        }
        ?>
        <style>
            .np-price-input {
                width:110px;
            }
        </style>
        <div class="wrap" style="margin-top:50px;">
            <form method="post" action="">
                <?php
                foreach($data as $d):
                    self::newsletterGenerateFields($d);
                endforeach;
                ?>
                <p class="submit">
                    <button name="np_submit" type="submit" class="button button-primary">Save Newsletter Pricing</button>
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * When hit submit or update button, this part should use option to update or insert new pricing data
     */
    public static function updateFields()
    {
        if(isset($_POST['np_submit'])):

            // Ready Made Content
            update_option( 'np_ready_made_content_request_1', (isset($_POST['np_ready_made_content_request_1'])) ? $_POST['np_ready_made_content_request_1'] :  0.35 );
            update_option( 'np_ready_made_content_request_2', (isset($_POST['np_ready_made_content_request_1'])) ? $_POST['np_ready_made_content_request_2'] :  0.70 );
            update_option( 'np_ready_made_content_request_3', (isset($_POST['np_ready_made_content_request_1'])) ? $_POST['np_ready_made_content_request_3'] :  1.00 );

            // Delivery Class
            update_option( 'np_a4_first_class',  (isset($_POST['np_a4_first_class']))  ? $_POST['np_a4_first_class']  :  0.35 );
            update_option( 'np_a4_second_class', (isset($_POST['np_a4_second_class'])) ? $_POST['np_a4_second_class'] :  0.70 );
            update_option( 'np_a5_first_class',  (isset($_POST['np_a5_first_class']))  ? $_POST['np_a5_first_class']  :  1.00 );
            update_option( 'np_a5_second_class', (isset($_POST['np_a5_second_class'])) ? $_POST['np_a5_second_class'] :  1.00 );

            // Delivery Type
            update_option( 'np_a5_self_mailer',        (isset($_POST['np_a5_self_mailer']))        ? $_POST['np_a5_self_mailer']        :  0.35 );
            update_option( 'np_a4_transparent_wallet', (isset($_POST['np_a4_transparent_wallet'])) ? $_POST['np_a4_transparent_wallet'] :  0.70 );
            update_option( 'np_a5_transparent_wallet', (isset($_POST['np_a5_transparent_wallet'])) ? $_POST['np_a5_transparent_wallet'] :  1.00 );

            // Advert Cost Size
            update_option( 'np_quarter_page', (isset($_POST['np_quarter_page'])) ? $_POST['np_quarter_page'] :  0.35 );
            update_option( 'np_half_page',    (isset($_POST['np_half_page']))    ? $_POST['np_half_page']    :  0.70 );
            update_option( 'np_full_page',    (isset($_POST['np_full_page']))    ? $_POST['np_full_page']    :  1.00 );


            self::updateStatusMessage(true);

        endif;
    }
    
    /**
     * print status message
     * @param $status
     */
    protected static function updateStatusMessage($status)
    {
        if($status) {
            print "<br><br><div class='alert alert-success' style='border: 1px solid #544c4c;width: auto;background: #5aa25a;padding: 20px;color: white;font-size: 17px;'>Pricing Information Successfully Updated.</div>";
        }
    }

    /**
     * generate fields
     * @param $fieldData
     */
    private static function  newsletterGenerateFields($fieldData)
    {  ?>
        <h3><?php print $fieldData['title'] ?></h3>
        <br/>
        <table class="form-table">
            <?php foreach($fieldData['fields'] as $field): ?>
                <tr>
                    <th scope="row"><label for="blogname"><?php print $field['title']; ?>&nbsp;&nbsp;</label></th>
                    <td><input type="text" class="sp-form-control np-price-input" name="<?php print $field['fieldName']; ?>" value="<?php print $field['fieldValue']; ?>"></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <hr><?php
    }
    /**
     * This will allow to preview the updated or saved data, printing via post request
     * so this should work after hitting submit or update button inside post form
     */
    public function printUpdateDebugging()
    {
        print "<pre>";
        print_r($_POST);
        print "</pre>";
    }
}