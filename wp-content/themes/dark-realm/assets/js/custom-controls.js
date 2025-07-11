(function(api) {

    api.sectionConstructor['dark-realm-upsell'] = api.Section.extend({
        attachEvents: function() {},
        isContextuallyActive: function() {
            return true;
        }
    });
  
    const businesssection_lists = ['banner', 'service'];
    businesssection_lists.forEach(business_homepage_scroll);
  
    function business_homepage_scroll(item) {
        item = item.replace(/-/g, '');
        wp.customize.section('dark_realm_' + item + '_section', function(section) {
            section.expanded.bind(function(isExpanding) {
                wp.customize.previewer.send(item, { expanded: isExpanding });
            });
        });
    }
  })(wp.customize);