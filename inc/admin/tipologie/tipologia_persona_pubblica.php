<?php
/**
 * Definisce post type Persona pubblica
 */
add_action( 'init', 'dci_register_post_type_persona_pubblica', 60 );
function dci_register_post_type_persona_pubblica() {
    /** scheda **/
    $labels = array(
        'name'          => _x( 'Persone Pubbliche', 'Post Type General Name', 'design_comuni_italia' ),
        'singular_name' => _x( 'Persona Pubblica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'add_new'       => _x( 'Aggiungi una Persona Pubblica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'add_new_item'  => _x( 'Aggiungi una nuova Persona Pubblica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'edit_item'       => _x( 'Modifica la Persona Pubblica', 'Post Type Singular Name', 'design_comuni_italia' ),
        'featured_image' => __( 'Immagine di riferimento della Persona Pubblica', 'design_comuni_italia' ),
    );

    $args   = array(
        'label'         => __( 'Persona pubblica', 'design_comuni_italia' ),
        'labels'        => $labels,
        'supports'      => array( 'title', 'editor', 'author', 'thumbnail'),
        //'taxonomies'    => array( 'post_tag' ),
        'hierarchical'  => false,
        'public'        => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-businessperson',
        'has_archive'   => true,
        'rewrite' => array('slug' => 'persona_pubblica','with_front' => false),
        'capability_type' => array('persona_pubblica', 'persone_pubbliche'),
        'map_meta_cap'    => true,
        'description'    => __( 'Questa Tipologia descrive le Persone Pubbliche dell\'Amministrazione', 'design_comuni_italia' ),
    );
    register_post_type('persona_pubblica', $args );

    remove_post_type_support( 'persona_pubblica', 'editor');
}

/**
 * Aggiungo label sotto il titolo
 */
add_action( 'edit_form_after_title', 'dci_persona_pubblica_add_content_after_title' );
function dci_persona_pubblica_add_content_after_title($post) {
    if($post->post_type == "persona_pubblica")
        _e('<span><i>il <b>Titolo</b> è il <b>Nome della Persona Pubblica</b>.</i></span><br><br>', 'design_comuni_italia' );
}

/**
 * Crea i metabox del post type Persona pubblica
 */
add_action( 'cmb2_init', 'dci_add_persona_pubblica_metaboxes' );
function dci_add_persona_pubblica_metaboxes() {

    $prefix = '_dci_persona_pubblica_';

    $cmb_user = new_cmb2_box( array(
        'id'               => $prefix . 'persona_box',
        'title'            => __( 'Persona', 'design_comuni_italia' ),
        'object_types'     => array( 'persona_pubblica' ),
        'context'      => 'normal',
        'priority'     => 'high',
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'nome',
        'name'        => __( 'Nome *', 'design_comuni_italia' ),
        'type' => 'text',
        'attributes' => array(
            'required' => 'required'
        )
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'cognome',
        'name'        => __( 'Cognome *', 'design_comuni_italia' ),
        'type' => 'text',
        'attributes' => array(
            'required' => 'required'
        )
    ) );

    $cmb_user->add_field( array(
        'name'    => __( 'Tipologia', 'design_comuni_italia' ),
        'desc'    => __( 'tipologia Persona (es: Persona Politica )', 'design_comuni_italia' ),
        'id'      => $prefix . 'tipologia_persona',
        'type'    => 'pw_select',
        'options' => array(
            'default' => null,
            'Persona Politica' => 'Persona Politica',
        )
    ) );

    $cmb_user->add_field( array(
        'name'    => __( 'Foto della Persona', 'design_comuni_italia' ),
        'desc'    => __( 'Inserire una fotografia che ritrae il soggetto descritto nella scheda', 'design_comuni_italia' ),
        'id'      => $prefix . 'foto',
        'type'    => 'file',
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'incarichi',
        'name'        => __( 'Incarichi', 'design_comuni_italia' ),
        'desc' => __( 'Collegamenti con gli incarichi' , 'design_comuni_italia' ),
        'type'    => 'pw_multiselect',
        'options' => dci_get_posts_options('incarico'),
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'organizzazioni',
        'name'    => __( 'Organizzazione' ),
        'desc' => __( 'Le organizzazioni di cui fa parte (es. Consiglio Comunale; es. Sistemi informativi)' , 'design_comuni_italia' ),
        'type'    => 'pw_multiselect',
        'options' => dci_get_posts_options('unita_organizzativa'),
        'attributes'    => array(
            //'required'    => 'required'
        ),
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'responsabile_di',
        'name'    => __( 'Responsabile di', 'design_comuni_italia' ),
        'desc' => __( 'Organizzazione di cui è responsabile.' , 'design_comuni_italia' ),
        'type'    => 'pw_select',
        'options' => dci_get_posts_options('unita_organizzativa'),
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'data_conclusione_incarico',
        'name'    => __( 'Data conclusione incarico', 'design_comuni_italia' ),
        'desc' => __( 'Data conclusione incarico.' , 'design_comuni_italia' ),
        'type'    => 'text_date',
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'competenze',
        'name'        => __( 'Competenze', 'design_comuni_italia' ),
        'desc' => __( 'Se Persona Politica, descrizione testuale del ruolo, comprensiva delle deleghe <br> OPPURE se Persona Amministrativa, descrizione dei compiti di cui si occupa la persona.' , 'design_comuni_italia' ),
        'type' => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 4, // rows="..."
            'teeny' => true, // output the minimal editor config used in Press This
        ),
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'deleghe',
        'name'        => __( 'Deleghe', 'design_comuni_italia' ),
        'desc' => __( 'Elenco delle deleghe a capo della persona' , 'design_comuni_italia' ),
        'type' => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 4, // rows="..."
            'teeny' => true, // output the minimal editor config used in Press This
        ),
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'biografia',
        'name'        => __( 'Biografia', 'design_comuni_italia' ),
        'desc' => __( 'Solo per Persona Politica: testo descrittivo che riporta la biografia della persona.' , 'design_comuni_italia' ),
        'type' => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 4, // rows="..."
            'teeny' => true, // output the minimal editor config used in Press This
        ),
    ) );

    $cmb_user->add_field( array(
        'name'       => __('Galleria di immagini', 'design_comuni_italia' ),
        'desc' => __( 'Solo per Persona Politica: gallery dell attività politica e istituzionale della persona.' , 'design_comuni_italia' ),
        'id'             => $prefix . 'gallery',
        'type' => 'file_list',
        // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
        'query_args' => array( 'type' => 'image' ), // Only images attachment
        'attributes'    => array(
            'data-conditional-id'     => $prefix.'tipologia_persona',
            'data-conditional-value'  => "Persona Politica",
        ),
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'punti_contatto',
        'name'        => __( 'Punti di contatto *', 'design_comuni_italia' ),
        'desc' => __( 'Telefono, mail o altri punti di contatto<br><a href="post-new.php?post_type=punto_contatto">Inserisci Punto di Contatto</a>' , 'design_comuni_italia' ),
        'type'    => 'pw_multiselect',
        'options' => dci_get_posts_options('punto_contatto'),
        'attributes'    => array(
            'required'    => 'required'
        ),
    ) );

    $cmb_user->add_field( array(
        'name'       => __('Curriculum Vitae', 'design_comuni_italia' ),
        'id'             => $prefix . 'curriculum_vitae',
        'type' => 'file',
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'situazione_patrimoniale',
        'name'        => __( 'Situazione patrimoniale', 'design_comuni_italia' ),
        'desc' => __( 'Solo per Persona Politica: situazione patrimoniale della persona' , 'design_comuni_italia' ),
        'type' => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 4, // rows="..."
            'teeny' => true, // output the minimal editor config used in Press This
        ),
    ) );

    $cmb_user->add_field( array(
        'id'             => $prefix . 'dichiarazione_redditi',
        'name' => __( 'Dichiarazione dei redditi' , 'design_comuni_italia' ),
        'desc'       => __('Obbligatorio per Persona che ha incarichi o cariche politiche: copia dell\'ultima dichiarazione dei redditi soggetti all\'imposta sui redditi delle persone fisiche Per il soggetto, il coniuge non separato e i parenti entro il secondo grado, ove gli stessi vi consentano (NB: dando eventualmente evidenza del mancato consenso) (NB: è necessario limitare, con appositi accorgimenti a cura dell\'interessato o della amministrazione, la pubblicazione dei dati sensibili)', 'design_comuni_italia' ),
        'type' => 'file_list',
    ) );

    $cmb_user->add_field( array(
        'id'             => $prefix . 'spese_elettorali',
        'name' => __( 'Spese elettorali' , 'design_comuni_italia' ),
        'desc'       => __('Obbligatorio per Persona che ha incarichi o cariche politiche:: dichiarazione concernente le spese sostenute e le obbligazioni assunte per la propaganda elettorale ovvero attestazione di essersi avvalsi esclusivamente di materiali e di mezzi propagandistici predisposti e messi a disposizione dal partito o dalla formazione politica della cui lista il soggetto ha fatto parte, con l\'apposizione della formula «sul mio onore affermo che la dichiarazione corrisponde al vero» (con allegate copie delle dichiarazioni relative a finanziamenti e contributi per un importo che nell\'anno superi 5.000 €)', 'design_comuni_italia' ),
        'type' => 'file_list',
    ) );

    $cmb_user->add_field( array(
        'id'             => $prefix . 'variazione_situazione_patrimoniale',
        'name' => __( 'Variazione situazione patrimoniale' , 'design_comuni_italia' ),
        'desc'       => __('Obbligatorio per Persona che ha incarichi o cariche politiche:: attestazione concernente le variazioni della situazione patrimoniale intervenute nell\'anno precedente e copia della dichiarazione dei redditi Per il soggetto, il coniuge non separato e i parenti entro il secondo grado, ove gli stessi vi consentano (NB: dando eventualmente evidenza del mancato consenso)', 'design_comuni_italia' ),
        'type' => 'file_list',
    ) );

    $cmb_user->add_field( array(
        'id'             => $prefix . 'altre_cariche',
        'name' => __( 'Altre cariche' , 'design_comuni_italia' ),
        'desc'       => __('Obbligatorio per Persona che ha incarichi o cariche politiche: i dati relativi all\'assunzione di altre cariche, presso enti pubblici o privati, ed i relativi compensi a qualsiasi titolo corrisposti.', 'design_comuni_italia' ),
        'type' => 'file_list',
    ) );

    $cmb_user->add_field( array(
        'id' => $prefix . 'ulteriori_informazioni',
        'name'        => __( 'Ulteriori informazioni', 'design_comuni_italia' ),
        'desc' => __( 'Ulteriori informazioni relative alla persona.' , 'design_comuni_italia' ),
        'type' => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 4, // rows="..."
            'teeny' => true, // output the minimal editor config used in Press This
        ),
    ) );

}