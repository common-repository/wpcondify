/**
 * External Dependencies
 */
// import classnames from 'classnames';

/**
 * WordPress Dependencies
 */
const { __ } = wp.i18n;
const { addFilter } = wp.hooks;
const { Fragment }	= wp.element;
const { InspectorAdvancedControls , InspectorControls }	= wp.editor;
const { createHigherOrderComponent } = wp.compose;
const { ToggleControl , Panel, PanelBody, PanelRow , SelectControl  } = wp.components;

// import { Panel, PanelBody, PanelRow } from '@wordpress/components';


//restrict to specific block names
const allowedBlocks = [ 'core/paragraph', 'core/heading' ];

/**
 * Add custom attribute for mobile visibility.
 *
 * @param {Object} settings Settings for the block.
 *
 * @return {Object} settings Modified settings.
 */
function addAttributes( settings ) {
	
	//check if object exists for old Gutenberg version compatibility
	//add allowedBlocks restriction
	if( typeof settings.attributes !== 'undefined' ){
	
		settings.attributes = Object.assign( settings.attributes, {
			enableWPCondify:{ 
				type: 'boolean',
				default: false,
            },
            selectedCondition:{
                type:'string',
                default: 'no_condition',
            }
		});
    
	}

	return settings;
}

/**
 * Add mobile visibility controls on Advanced Block Panel.
 *
 * @param {function} BlockEdit Block edit component.
 *
 * @return {function} BlockEdit Modified block edit component.
 */
const withAdvancedControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {

		const {
			name,
			attributes,
			setAttributes,
			isSelected,
		} = props;

		const {
			enableWPCondify,selectedCondition
		} = attributes;
        
        // console.log(wpcondify.conditionsList);
        // let conditionsList = [{
        //     lable: '- No Condition -',
        //     value: 'no_condition'
        // }];
        let availableList = wpcondify.conditionsList;

        var options = [];
        options.push( { value: 0, label: '- No Condition -' } );
        availableList.forEach((con) => {
            options.push({ value: con.value , label: con.label})
        } );

        console.log(options)
        // console.log(availableList);
        // for(let key of Object.keys(availableList)){
        //     // console.log('Label ' + key + ' and value : ' + availableList[key]);
        //     conditionsList.push(
        //         {
        //             label: key , 
        //             value: availableList[key]
        //         }
        //         );
        // }

        // console.log(conditionsList);
        // conditionsList.forEach(e => console.log(e))
		
		return (
			<Fragment>
				<BlockEdit {...props} />
		
				{ isSelected  &&
					<InspectorControls>
                        <Panel>
                            <PanelBody title="WPCondify"  initialOpen={ true }>
                                <PanelRow>
                                <ToggleControl
                                    label={ __( 'Apply Condition' ) }
                                    checked={ !! enableWPCondify }
                                    onChange={ () => setAttributes( {  enableWPCondify: ! enableWPCondify } ) }
                            
						        />
                                </PanelRow>

                                {  enableWPCondify &&
                                <SelectControl
                                    label="Select Condtion"
                                    value={ selectedCondition }
                                    options={ options}
                                    onChange={ ( conditionID ) => { setAttributes( { selectedCondition: conditionID } ) } }
                                />
                                }
                                
                                
                            </PanelBody>
                        </Panel>
						
					</InspectorControls>
				}

			</Fragment>
		);
	};
}, 'withAdvancedControls');

/**
 * Add custom element class in save element.
 *
 * @param {Object} extraProps     Block element.
 * @param {Object} blockType      Blocks object.
 * @param {Object} attributes     Blocks attributes.
 *
 * @return {Object} extraProps Modified block element.
 */
function applyExtraClass( extraProps, blockType, attributes ) {

    const { enableWPCondify , selectedCondition } = attributes;
    console.log('Hello world');
    
    console.log(selectedCondition);
	
	// //check if attribute exists for old Gutenberg version compatibility
	// //add class only when enableWPCondify = false
	// //add allowedBlocks restriction
	// if ( enableWPCondify  ) {
	// 	extraProps.className = 'wpcondify-hidden';
    // }
    

    return extraProps;
    
}

//add filters

addFilter(
	'blocks.registerBlockType',
	'editorskit/custom-attributes',
	addAttributes
);

addFilter(
	'editor.BlockEdit',
	'editorskit/custom-advanced-control',
	withAdvancedControls
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'editorskit/applyExtraClass',
	applyExtraClass
);