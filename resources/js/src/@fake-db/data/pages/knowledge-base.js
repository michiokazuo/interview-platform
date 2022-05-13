import mock from '@/@fake-db/mock'
/* eslint-disable global-require */
const data = {
  // knowledge base
  knowledgeBase: [
    {
      id: 1,
      category: 'support-interview',
      img: require('@/assets/images/illustration/marketing.svg'),
      title: 'Hỗ trợ phỏng vấn',
      desc: 'Có hệ thống phục vụ quá trình phỏng vấn.',
    },
    {
      id: 2,
      category: 'api-questions',
      img: require('@/assets/images/illustration/api.svg'),
      title: 'Hệ thống câu hỏi',
      desc: 'Phong phú và đa dạng từ các nguồn khác nhau (Stackoverflow,...).',
    },
    {
      id: 3,
      category: 'personalization',
      img: require('@/assets/images/illustration/personalization.svg'),
      title: 'Cá nhân hóa',
      desc: 'Tối ưu hóa cho người dùng.',
    },
    {
      id: 5,
      category: 'manage-process',
      img: require('@/assets/images/illustration/email.svg'),
      title: 'Quản lý quy trình',
      desc: 'Hỗ trợ doanh nghiệp quản lý quy trình tuyển dụng theo từng dự án.',
    },
    {
      id: 6,
      category: 'support-contact',
      img: require('@/assets/images/illustration/demand.svg'),
      title: 'Hỗ trợ người dùng',
      desc: 'Luôn sẵn sàng phục vụ.',
    },
  ],
  categoryData: [
    {
      id: 0,
      title: 'Quản lý thông tin',
      icon: 'SettingsIcon',
      iconColor: 'text-primary',
      questions: [
        {
          id: 0,
          question: 'Bảo mật thông tin',
          slug: 'how-secure-is-my-password',
        },
        {
          id: 1,
          question: 'Thân thiện dễ sử dụng',
          slug: 'convenient',
        },
      ],
    },
    {
      id: 1,
      title: 'Câu hỏi',
      icon: 'LinkIcon',
      iconColor: 'text-success',
      questions: [
        {
          id: 0,
          question: 'Phong phú',
          slug: 'question-1',
        },
        {
          id: 1,
          question: 'Đa dạng',
          slug: 'question-2',
        },
        {
          id: 2,
          question: 'Từ các nguồn phổ biến (Stackoverflow,...)',
          slug: 'question-3',
        },
      ],
    },
    {
      id: 2,
      title: 'Ứng dụng di động',
      icon: 'SmartphoneIcon',
      iconColor: 'text-info',
      questions: [
        {
          id: 0,
          question: 'Đang phát triển',
          slug: 'question-4',
        },
      ],
    },
    {
      id: 3,
      title: 'Tùy chỉnh giao diện',
      icon: 'ToolIcon',
      iconColor: '',
      questions: [
        {
          id: 0,
          question: 'Màu sắc',
          slug: 'customization',
        },
        {
          id: 1,
          question: 'Layout',
          slug: 'upgrading',
        },
        {
          id: 2,
          question: '...',
          slug: 'customizing-your-theme',
        },
      ],
    },
    {
      id: 4,
      title: 'Liên lạc và hỗ trợ',
      icon: 'InfoIcon',
      iconColor: '',
      questions: [
        {
          id: 0,
          question: 'Luôn sẵn sàng phục vụ',
          slug: 'customization',
        },
        {
          id: 1,
          question: 'Thông tin chính xác',
          slug: 'upgrading',
        },
        {
          id: 2,
          question: 'Nhanh chóng và tiện lợi',
          slug: 'customizing-your-theme',
        },
      ],
    },
  ],
  questionData: {
    title: 'Why Was My Developer Application Rejected?',
    lastUpdated: '10 Dec 2018',
    relatedQuestions: [
      {
        id: 0,
        question: 'How Secure Is My Password?',
      },
      {
        id: 1,
        question: 'Can I Change My Username?',
      },
      {
        id: 2,
        question: 'Where Can I Upload My Avatar?',
      },
      {
        id: 3,
        question: 'How Do I Change My Timezone?',
      },
      {
        id: 4,
        question: 'How Do I Change My Password?',
      },
    ],
    // ! Here we have used require for image source but in API it shall be URL of live image, this is just for demo purpose
    content: `<p>It has been said that astronomy is a humbling and character-building experience. There is perhaps no better demonstration of the folly of human conceits than this distant image of our tiny world. To me, it underscores our responsibility to deal more kindly with one another, and to preserve and cherish the pale blue dot, the only home we’ve ever known. The Earth is a very small stage in a vast cosmic arena. Think of the rivers of blood spilled by all those generals and emperors so that, in glory and triumph, they could become the momentary masters of a fraction of a dot. Think of the endless cruelties visited by the inhabitants of one corner of this pixel on the scarcely distinguishable inhabitants of some other corner, how frequent their misunderstandings, how eager they are to kill one another, how fervent their hatreds.</p><p class="ql-align-center"><img class="img-fluid w-100" src="${require('@/assets/images/pages/kb-image.jpg')}"></p></p><h5>Houston</h5><p>that may have seemed like a very long final phase. The auto targeting was taking us right into a … crater, with a large number of big boulders and rocks … and it required … flying manually over the rock field to find a reasonably good area.</p><ul><li><a href="javascript:void(0)" rel="noopener noreferrer" >I am a stranger. I come in peace. Take me to your leader and there will be a massive reward for you in eternity.</a></li><li><a href="javascript:void(0)" rel="noopener noreferrer" >It’s just mind-blowingly awesome. I apologize, and I wish I was more articulate, but it’s hard to be articulate when your mind’s blown—but in a very good way.</a></li><li><a href="javascript:void(0)" rel="noopener noreferrer" >A good rule for rocket experimenters to follow is this</a></li></ul>`,
  },
  // category
}
/* eslint-disable global-require */
mock.onGet('/kb/data/knowledge_base').reply(() => [200, data.knowledgeBase])
mock.onGet('/kb/data/category').reply(() => [200, data.categoryData])
mock.onGet('/kb/data/question').reply(() => [200, data.questionData])
