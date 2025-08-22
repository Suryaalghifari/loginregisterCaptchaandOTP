<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter CAPTCHA Helper (patched for PHP 8.1+)
 * - Cast koordinat & bounds ke int agar tidak muncul warning
 */

if ( ! function_exists('create_captcha'))
{
    /**
     * Create CAPTCHA
     *
     * @param array  $data
     * @param string $img_path
     * @param string $img_url
     * @param string $font_path
     * @return array|bool
     */
    function create_captcha($data = '', $img_path = '', $img_url = '', $font_path = '')
    {
        $defaults = array(
            'word'        => '',
            'img_path'    => '',
            'img_url'     => '',
            'img_width'   => '150',
            'img_height'  => '30',
            'font_path'   => '',
            'expiration'  => 7200,
            'word_length' => 8,
            'font_size'   => 16,
            'img_id'      => '',
            'pool'        => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors'      => array(
                'background' => array(255, 255, 255),
                'border'     => array(153, 102, 102),
                'text'       => array(204, 153, 153),
                'grid'       => array(255, 182, 182),
            ),
        );

        foreach ($defaults as $key => $val)
        {
            if ( ! is_array($data) && empty($$key))
            {
                $$key = $val;
            }
            else
            {
                $$key = isset($data[$key]) ? $data[$key] : $val;
            }
        }

        // Pastikan numeric ke int untuk dimensi/font
        $img_width  = (int) $img_width;
        $img_height = (int) $img_height;
        $font_size  = (int) $font_size;

        if ( ! extension_loaded('gd'))
        {
            log_message('error', 'create_captcha(): GD extension is not loaded.');
            return FALSE;
        }

        if ($img_path === '' || $img_url === '')
        {
            log_message('error', 'create_captcha(): $img_path and $img_url are required.');
            return FALSE;
        }

        if ( ! is_dir($img_path) || ! is_really_writable($img_path))
        {
            log_message('error', "create_captcha(): '{$img_path}' is not a dir, nor is it writable.");
            return FALSE;
        }

        // Remove old images
        $now = microtime(TRUE);
        $current_dir = @opendir($img_path);
        while ($filename = @readdir($current_dir))
        {
            if (in_array(substr($filename, -4), array('.jpg', '.png'))
                && (str_replace(array('.jpg', '.png'), '', $filename) + $expiration) < $now)
            {
                @unlink($img_path.$filename);
            }
        }
        @closedir($current_dir);

        // Generate word jika belum ada
        if (empty($word))
        {
            $word = '';
            $pool_length = strlen($pool);
            $rand_max = $pool_length - 1;

            if (function_exists('random_int'))
            {
                try {
                    for ($i = 0; $i < $word_length; $i++) {
                        $word .= $pool[random_int(0, $rand_max)];
                    }
                } catch (Exception $e) {
                    $word = '';
                }
            }
        }

        if (empty($word))
        {
            if (!isset($pool_length)) $pool_length = strlen($pool);
            if ($pool_length > 256) return FALSE;

            $security = get_instance()->security;
            if (($bytes = $security->get_random_bytes($pool_length)) !== FALSE)
            {
                $byte_index = $word_index = 0;
                while ($word_index < $word_length)
                {
                    if ($byte_index === $pool_length)
                    {
                        for ($i = 0; $i < 5; $i++)
                        {
                            if (($bytes = $security->get_random_bytes($pool_length)) === FALSE)
                            {
                                continue;
                            }
                            $byte_index = 0;
                            break;
                        }
                        if ($bytes === FALSE)
                        {
                            $word = '';
                            break;
                        }
                    }

                    list(, $rand_index) = unpack('C', $bytes[$byte_index++]);
                    if (!isset($rand_max)) $rand_max = $pool_length - 1;
                    if ($rand_index > $rand_max) continue;

                    $word .= $pool[$rand_index];
                    $word_index++;
                }
            }
        }

        if (empty($word))
        {
            if (!isset($rand_max)) $rand_max = strlen($pool) - 1;
            for ($i = 0; $i < $word_length; $i++)
            {
                $word .= $pool[mt_rand(0, (int)$rand_max)];
            }
        }
        elseif ( ! is_string($word))
        {
            $word = (string) $word;
        }

        // Angle & posisi
        $length = strlen($word);
        $angle  = ($length >= 6) ? mt_rand(-($length-6), ($length-6)) : 0;
        $x_axis = mt_rand(6, (int)((360 / max(1,$length)) - 16));
        $y_axis = ($angle >= 0) ? mt_rand((int)$img_height, (int)$img_width) : mt_rand(6, (int)$img_height);

        // Create image
        $im = function_exists('imagecreatetruecolor')
            ? imagecreatetruecolor($img_width, $img_height)
            : imagecreate($img_width, $img_height);

        // Colors
        is_array($colors) OR $colors = $defaults['colors'];
        foreach (array_keys($defaults['colors']) as $key)
        {
            is_array($colors[$key]) OR $colors[$key] = $defaults['colors'][$key];
            $colors[$key] = imagecolorallocate($im, (int)$colors[$key][0], (int)$colors[$key][1], (int)$colors[$key][2]);
        }

        // Background
        imagefilledrectangle($im, 0, 0, (int)$img_width, (int)$img_height, $colors['background']);

        // Spiral grid (cast koordinat)
        $theta   = 1;
        $thetac  = 7;
        $radius  = 16;
        $circles = 20;
        $points  = 32;

        for ($i = 0, $cp = ($circles * $points) - 1; $i < $cp; $i++)
        {
            $theta += $thetac;
            $rad = $radius * ($i / $points);
            $x  = ($rad * cos($theta)) + $x_axis;
            $y  = ($rad * sin($theta)) + $y_axis;
            $theta += $thetac;
            $rad1 = $radius * (($i + 1) / $points);
            $x1 = ($rad1 * cos($theta)) + $x_axis;
            $y1 = ($rad1 * sin($theta)) + $y_axis;

            imageline($im, (int)$x, (int)$y, (int)$x1, (int)$y1, $colors['grid']);
            $theta -= $thetac;
        }

        // Teks
        $use_font = ($font_path !== '' && file_exists($font_path) && function_exists('imagettftext'));
        if ($use_font === FALSE)
        {
            ($font_size > 5) && $font_size = 5;
            $x = mt_rand(0, (int)($img_width / max(1, ($length / 3))));
            $y = 0;
        }
        else
        {
            ($font_size > 30) && $font_size = 30;
            $x = mt_rand(0, (int)($img_width / max(1, ($length / 1.5))));
            $y = $font_size + 2;
        }

        for ($i = 0; $i < $length; $i++)
        {
            if ($use_font === FALSE)
            {
                $y = mt_rand(0, (int)($img_height / 2));
                imagestring($im, (int)$font_size, (int)$x, (int)$y, $word[$i], $colors['text']);
                $x += ($font_size * 2);
            }
            else
            {
                $y = mt_rand((int)($img_height / 2), (int)($img_height - 3));
                imagettftext(
                    $im,
                    (float)$font_size,      // size boleh float
                    (float)$angle,          // angle boleh float
                    (int)$x, (int)$y,       // ← wajib int
                    $colors['text'],
                    $font_path,
                    $word[$i]
                );
                $x += $font_size;
            }
        }

        // Border
        imagerectangle($im, 0, 0, (int)($img_width - 1), (int)($img_height - 1), $colors['border']);

        // Output
        $img_url = rtrim($img_url, '/').'/';

        if (function_exists('imagejpeg'))
        {
            $img_filename = $now.'.jpg';
            imagejpeg($im, $img_path.$img_filename);
        }
        elseif (function_exists('imagepng'))
        {
            $img_filename = $now.'.png';
            imagepng($im, $img_path.$img_filename);
        }
        else
        {
            imagedestroy($im);
            return FALSE;
        }

        $img = '<img '.($img_id === '' ? '' : 'id="'.htmlspecialchars($img_id, ENT_QUOTES, 'UTF-8').'"')
             .' src="'.$img_url.$img_filename
             .'" style="width: '.$img_width.'px; height: '.$img_height.'px; border:0;" alt=" " />';

        imagedestroy($im);

        return array('word' => $word, 'time' => $now, 'image' => $img, 'filename' => $img_filename);
    }
}
